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
    body{background: center 0 no-repeat url("images/bg.jpg"); margin: 0; padding: 0; font-family: arial; direction: rtl; text-align: right;}

    .cb{clear: both;}

    .content{width: 855px; margin: 0 auto; margin-top: 30px;}

    #cal-links{width: 740px; margin: 0 auto; margin-top: 300px;}
    .cal-link{width: 232px; height: 75px; background: 0 0 no-repeat url("images/cal-link.png"); display: block; margin-left: 19px; float: left; text-decoration: none; text-align: left; position: relative;}
    .cal-link:first-child{margin-left: 5px;}
    .cal-link .txt{color: #6f6f6f; display: inline-block; font-size: 22px; line-height: 22px; position: absolute; top: 25px; right: 25px;}

    /*.cal-link.google .txt{margin-top: 15px;}*/

    .cal-link .ico{width: 48px; height: 32px; display: inline-block; position: absolute; left: 15px; top: 20px; background-repeat: no-repeat; background-image: url("images/ico.png");}
    .cal-link.ical .ico{background-position: 0 0;}
    .cal-link.google .ico{background-position: 0 -32px;}
    .cal-link.outlook .ico{background-position: 0 -64px;}

    #txt{text-align: center; margin-top: 31px;}
    p{font-size: 18px; margin: 0;}
    p strong{color: #b62070;}
  </style>
</head>
<body>

<div class="content">
    <div id="cal-links">
        <a class="cal-link ical" target="_blank" href="http://inevermiss.net/cal/sub<?php echo ($calId) ? '/id/' . $calId : '';?><?php echo ($ctgId) ? '/ctgId/' . $ctgId : '';?>/ct/any/ref/widget/cal.ics">
            <span class="ico">&nbsp;</span>
            <span class="txt">לוח שנה אחר</span>
        </a>
        <a class="cal-link outlook" href="webcal://inevermiss.net/cal/sub<?php echo ($calId) ? '/id/' . $calId : '';?><?php echo ($ctgId) ? '/ctgId/' . $ctgId : '';?>/ct/outlook/ref/widget/cal.ics">
            <span class="ico">&nbsp;</span>
            <span class="txt">אאוטלוק</span>
        </a>
        <a class="cal-link google" target="_blank" href="http://inevermiss.net/cal/sub<?php echo ($calId) ? '/id/' . $calId : '';?><?php echo ($ctgId) ? '/ctgId/' . $ctgId : '';?>/ct/google/ref/widget/cal.ics">
            <span class="ico">&nbsp;</span>
            <span class="txt">גוגל</span>
        </a>

        <div class="cb"></div>
    </div>
    <p id="txt"><strong>בחר ביומן בו אתה משתמש</strong> לקבלת כל העידכונים</p>
</div>
</body>
</html>