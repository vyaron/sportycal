<?php
class SportycalPDF extends UFPDF
{

    function PrintTable($events) {
        
        $this->SetFont('arial','',12);

        
        //Column widths
        //$w         = array(120,20,40);
        $w          = $this->figureCollumnWidth($events);
        $header     = array("What", "Where", "When");
        $xStart     = 20;
        
        $this->setX($xStart);
        $this->SetFont('','B');
        //Header
        for($i=0;$i<count($header);$i++) {
            $this->Cell($w[$i],7,$header[$i],0);
        }
         
        $this->Ln();
        $this->setX($xStart);
        $this->SetFont('','');
         
        //Events
        foreach($events as $event)        {
            $this->Cell($w[0],6,$event->getName(),'0');
            $this->Cell($w[1],6,$event->getLocation(),'0');
            $this->Cell($w[2],6,$event->getDateForDisplay(),'0');
            $this->Ln(8);
            $this->setX($xStart);
        }
        //Closure line
        //$this->Cell(array_sum($w),0,'','T');
         
    }



//Page header
function PrintHeader($title, $imgPath)
{
    $this->AddFont('Arial', '', 'font/arial.php');
    $this->AddFont('ArialBd', '', 'font/arialbd.php');

    $this->setY(20);
    //Logo
    $this->Image(sfConfig::get('sf_web_dir') . '/images/pdf/logo.gif',10,8, 50);

    //Right image
    $this->Image(sfConfig::get('sf_web_dir') . $imgPath ,190,8, 10);

    $this->Ln(10);

    //Arial bold 15
     $this->SetFont('arialbd','',15);

    //Move to the right
    $this->Cell(80);
    //Title

    $this->Cell(30,10,$title,0,0,'C');
    $this->Ln(20);
}

  //Page footer
  function Footer()
  {
    //$this->Ln(2);
    $y = $this->GetY();
    $y = 275;
    //$this->Cell(0,10,'Y:' . $y,0,0,'C');

    $x = 20;
    $xDiff=25;
    //$this->SetX(80);
    $this->Image(sfConfig::get('sf_web_dir') . '/images/pdf/i1.gif', $x, $y, 16 );
    $x += $xDiff;    
    $this->Image(sfConfig::get('sf_web_dir') . '/images/pdf/i2.gif', $x , $y, 16);
    $x += $xDiff;        
    $this->Image(sfConfig::get('sf_web_dir') . '/images/pdf/i3.gif', $x, $y, 16);

    $x += $xDiff;        
    $this->Image(sfConfig::get('sf_web_dir') . '/images/pdf/i4.gif', $x, $y, 16);

    //Position at 1.5 cm from bottom
    //$this->SetY(-15);


    //Arial italic 8
    //$this->SetFont('Arial','I',8);
    $this->SetFont('Arial','',8);
    $this->setY(283);
    $this->Cell(0,10,'We have much more... www.sportYcal.com',0,0,'R');

  }

  
    private function figureCollumnWidth($events) {


        $WIDTH              = 150;
        $maxNameSize        = 50;
        $maxLocationSize    = 10;

        foreach($events as $event)        {
            $eName          = $event->getName();
            $eLocation      = $event->getLocation();
            $eNameSize      = strlen($eName);
            $eLocationSize  = strlen($eLocation);
            
            if ($eNameSize > $maxNameSize)          $maxNameSize     = $eNameSize;
            if ($eLocationSize > $maxLocationSize)  $maxLocationSize = $eLocationSize;
        }

        $colLocationSize         = round($maxLocationSize * 2.3);
        $colNameSize             = $WIDTH - $colLocationSize;
        
        return array($colNameSize, $colLocationSize, 40);
    }



}

?>
