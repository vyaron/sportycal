<?php
use_stylesheet('/bundle/bootstrap/css/bootstrap.min.css');
use_stylesheet('/bundle/bootstrap/css/bootstrap-responsive.min.css');
use_stylesheet('/css/fifaWorldCup.css');

slot('title',sprintf('%s Calendars', $category->getName()));
slot('keywords', $category->getName() . ", " .Cal::getKeywordsForCals($cals));


$userSession = sfContext::getInstance()->getUser();
$culture = $userSession->getCulture();
if ($culture === 'he_IL' || $culture === 'he') $RTL = true;
else $RTL = false;


?>

<div class="container">

    <h1><?php echo __("FIFA World Cup 2014")?></h1>
    <hr/>
<!--    <form>-->
<!--        <fieldset>-->
            <div class="row-fluid">
                <div class="span8 <?php echo ($RTL)? 'pull-right' : '' ?>">
                    <select id="teams" name="calId" class="input-block-level">
                        <option value="0"><?php echo __("Select your team") ?></option>
                        <?php foreach ($cals as $cal):?>
                            <option value="<?php echo $cal->getId();?>" data-imagesrc="<?php echo $cal->getTheImagePath();?>"><?php echo $cal->getName();?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="span4">
                    <a id="download-calendar" class="btn btn-success btn-block" disabled="disabled" data-href="<?php echo sfConfig::get('app_domain_fullNeverMiss')?>/nm/addToCalendar/?calId=" target="_blank">
                        <?php echo __("Add to my Calendar") ?>
                    </a>
                </div>
            </div>
<!--        </fieldset>-->
<!--    </form>-->

    <p><?php echo __("(Don't worry, even if your team doesn't make it, you will still get the rest of the games, all the way to the finals.)") ?></p>

    <hr/>

    <p>
        <strong><?php echo __("Big fan?...") ?> </strong>
        <a href="<?php echo sfConfig::get('app_domain_fullNeverMiss')?>/nm/addToCalendar/?ctgId=<?php echo $ctgId?>" target="_blank"><?php echo __("Download ALL Games") ?></a>
    </p>
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
use_javascript('/js/fifaWorldCup.js');
?>