<?php
use_stylesheet('/bundle/bootstrap/css/bootstrap.min.css');
use_stylesheet('/bundle/bootstrap/css/bootstrap-responsive.min.css');
use_stylesheet('/css/fifaWorldCup.css');
?>

<div class="container">
    <h1>Fifa world cup 2014</h1>

    <hr/>

<!--    <form>-->
<!--        <fieldset>-->
            <div class="row-fluid">
                <div class="span8">
                    <select id="teams" name="calId" class="input-block-level">
                        <option value="0">Select your team</option>
                        <?php foreach ($cals as $cal):?>
                            <option value="<?php echo $cal->getId();?>" data-imagesrc="<?php echo $cal->getTheImagePath();?>"><?php echo $cal->getName();?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="span4">
                    <a id="download-calendar" class="btn btn-success btn-block" disabled="disabled" data-href="<?php echo sfConfig::get('app_domain_fullNeverMiss')?>/nm/addToCalendar/?calId=" target="_blank">Download calendar</a>
                </div>
            </div>
<!--        </fieldset>-->
<!--    </form>-->

    <p>(Don't worry, even if your team doesn't make it, you will still get the rest of the games, all the way to the finals.)</p>

    <hr/>

    <p>
        <strong>Big fan?... </strong>
        <a href="<?php echo sfConfig::get('app_domain_fullNeverMiss')?>/nm/addToCalendar/?ctgId=<?php echo $ctgId?>" target="_blank">Download ALL Games</a>
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