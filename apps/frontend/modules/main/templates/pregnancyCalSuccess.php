<?php
use_stylesheet('/css/pregnancyCal.css');

slot('title', 'קלנדר הריון');
slot('keywords', 'קלנדר הריון' );


$userSession = sfContext::getInstance()->getUser();
$culture = $userSession->getCulture();
if ($culture === 'he_IL' || $culture === 'he') $RTL = true;
else $RTL = false;


?>

    <div class="main-img">
        <div class="col-sm-8 col-sm-push-2">
            <header>
                <h1 class="wow">קלנדר הריון</h1>
                <h2 class="wow">כדי שלא תפספסי שום שלב חשוב</h2>
            </header>
        </div>
        <div class="col-xs-8 col-xs-push-2 date-input">
            <div class="col-xs-12">
                <input id="datepicker" type="text" class="form-control input-lg wow slideInLeft" placeholder="תאריך ווסת אחרון" data-wow-duration="2s">
                <button id="btnCreateCal" class="btn btn-success btn-lg wow slideInRight btn-cal" data-wow-duration="2s"><i class="fa fa-calendar"></i>
                    צור לוח שנה
                </button>
            </div>
        </div>
        <img id="bg" src="/images/calender-bg2.png" class="img-responsive" alt="calender">
        <div class="note one animated">
            אולטרסאונד ראשון
        </div>
        <div class="note two animated">
            שקיפות עורפית
        </div>
        <div class="note three animated">
            יוגה לנשים הרות
        </div>
        <div class="note four animated">
            קורס הכנה ללידה
        </div>
        <div class="note five animated">
            בדיקת העמסת סוכר
        </div>
        <div class="note six animated">
            סקירת מערכות מורחבת
        </div>

    </div>



<script type="text/javascript">
    //Google Analytics
    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-42665653-1', 'inevermiss.net');
    ga('send', 'pageview');
</script>



<?php

use_javascript('/js/pregnancyCal.js');

?>