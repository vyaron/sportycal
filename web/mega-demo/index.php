<?php 

$calId  = isset($_GET['calId']) ? $_GET['calId'] : '';
$ctgId  = isset($_GET['ctgId']) ? $_GET['ctgId'] : '';

?>
<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8"/>
  <link rel="SHORTCUT ICON" type="image/vnd.microsoft.icon" href="favicon.ico" />
  <style>
    body{margin:0; padding:0; direction:rtl; text-align:right; background-color:#ccc;}
    
    .cb{clear:both;}
  
    #container{margin:20px auto;  background-color: #FFFFFF; width:260px; box-shadow: 1px 1px 10px #666666;}
    #content{padding: 30px;}
    #logo{float:right;}          
  </style>
</head>
<body>

<div id="container">
  <div id="content">
    <div>
      <img id="logo" src="logo_mega.gif"/>
      <div class="cb"></div>
    </div>

    <div class="nm-follow" data-cal-id="<?php echo $calId;?>" data-ctg-id="<?php echo $ctgId;?>" data-language="he" data-btn-style="list" data-color="default" data-upcoming="5"></div>
  
  </div>
</div>

<script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//inevermiss.net/w/neverMiss/all.js";fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'never-miss-jssdk'));</script>
</body>
</html>