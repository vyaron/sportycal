<?php

define('CAPTCHA_FONT',dirname(__FILE__).'/fonts/MTCORSVA.TTF');


class Captcha
{
   // Font Settings
   //private m_szFontFace =  "fonts/MTCORSVA.TTF";
   private $m_iFontSize = 20;
   
   // Image Object
   private $m_oImage = null;

   // Image Bounds
   private $m_aImageSize = array( 110, 40 ); // Image Width & Height;

   // Image Text
   private $m_szImageText = '';
   // Image file name
   private $m_szImageFileName = '';

   private function generateKey( $iLen )  {
       $szRandStr = md5( uniqid( rand(), true ) );
       $iRandIdx = rand( 0, (strlen($szRandStr) - $iLen - 1) );
       $szRandStr = substr( $szRandStr, $iRandIdx, $iLen );
       
       // Replace O's and 0's to reduce confusion
       $szRandStr = str_replace( "O", "X", $szRandStr );
       $szRandStr = str_replace( "0", "4", $szRandStr );
       
       $imageText = strtoupper( $szRandStr );
       return $imageText;
   }

   public function getCaptchaStr() {   
		return $this->m_szImageText;
   }
   public function getCaptchaFileName() {   
		return $this->m_szImageFileName;
   }
   
   public function create()   {
       $iTextLen = 5;
       
       $this->m_szImageText 		= $this->generateKey($iTextLen);
       $this->m_szImageFileName 	= $this->generateKey(10);
       
       // Create Image
       $this->m_oImage = imagecreate( $this->m_aImageSize[0], $this->m_aImageSize[1] );

       // Get Colors
       $oColorFG = imagecolorallocate( $this->m_oImage, 143, 168, 183 );
       $oColorBG = imagecolorallocate( $this->m_oImage, 30, 42, 49 );

       // Set Background Color of Image
       imagefilledrectangle( $this->m_oImage, 0, 0, $this->m_aImageSize[0], $this->m_aImageSize[1], $oColorFG );
       imagefilledrectangle( $this->m_oImage, 1, 1, $this->m_aImageSize[0]-2, $this->m_aImageSize[1]-2, $oColorBG );

       // Obfuscate Image
       $this->obfuscateImage();
       
       
       //$this->m_szImageText = "9C988";
       // Write Verification String to Image
       for( $i = 0; $i < $iTextLen; $i++ )
           $this->WriteTTF( (10 + ($i * 18)), (24 + rand(0, 5)), rand(-15, 15), $this->m_szImageText[$i] );
       
       $fileName = self::getImageFilePath($this->m_szImageFileName);
       imagegif( $this->m_oImage, $fileName);

       // Free Image Resources
       imagedestroy( $this->m_oImage );
       return $fileName;
   }


   private function obfuscateImage()
   {
       $oColor = imagecolorallocate( $this->m_oImage, 143, 168, 183 );
       
       // Random Pixels
       for( $x = 0; $x < $this->m_aImageSize[0]; $x += rand( 3, 7 ) )
           for( $y = 0; $y < $this->m_aImageSize[1]; $y += rand( 3, 7 ) )
               imagesetpixel( $this->m_oImage, $x, $y, $oColor );
       
       // Random Diagonal Lines
       for( $x = 0; $x < $this->m_aImageSize[0]; $x += rand( 15, 25 ) )
           imageline( $this->m_oImage, $x, 0, $x + rand( -50, 50 ), $this->m_aImageSize[1], $oColor );

       for( $y = 0; $y < $this->m_aImageSize[1]; $y += rand( 15, 25 ) )
           imageline( $this->m_oImage, 0, $y, $this->m_aImageSize[0], $y + rand( -50, 50 ), $oColor );

       return;
   }

   function WriteTTF( $iLocX, $iLocY, $iAngle, $szText )
   {
       $oColor = imagecolorallocate( $this->m_oImage, 255, 255, 255 );
       imagettftext( $this->m_oImage, $this->m_iFontSize, $iAngle, $iLocX, $iLocY, $oColor, CAPTCHA_FONT, $szText );
   }
   
   public static function getImageFilePath($imageFileName, $root=true) {
   	if ($root) $path = sfConfig::get('app_captha_rootDir') . $imageFileName.".gif";
   	else $path = sfConfig::get('app_captha_dir') . $imageFileName.".gif";
   	
   	return $path;
   }
   
   public static function deleteImageFile($imageFileName) {
	$path = self::getImageFilePath($imageFileName);
   	if (file_exists($path))  unlink($path);
   }
}
?>
