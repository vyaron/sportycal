<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Widget Settings &bull; Wix</title>
    <link rel="stylesheet" href="/bundle/wix-ui-lib-v2.1.6/dist/ui-lib.min.css"/>
    <link rel="stylesheet" href="/bundle/wix-ui-lib-v2.1.6/dist/ui-lib-dashboard.min.css"/>
</head>
<body>

<header class="box">
    <div class="logo">
        <img width="86" src="/images/wix/wix_icon.png" alt="iNeverMiss Logo"/>
    </div>

    <?php if (!$user):?>
        <div class="loggedOut">
            <p>
                Display your events and allow your customers to subscribe using their personal calendars.<br/>
                It's Easy:
            <ol style="font-size:12px; color: #484848; list-style-position:inside;">
                <li>Add your events to the calendar.</li>
                <li>Your users see your events in your site, and subscribe from any device or calendar they use!</li>
                <li>Any changes you make are automatically reflected in all your users' calendars.</li>
            </ol>
            </p>

            <p>
                Become a part for your customer's day!<br/>
                and your users will never miss a sale, a show or an event.
            </p>

            <div class="login-panel">
                <p class="create-account">Don't have an<br/>account? <strong><a id="create-account" href="">Create one</a></strong></p>
                <button class="submit uilib-btn connect">Connect account</button>
            </div>
        </div>
    <?php endif;?>

    <?php if ($user):?>
        <div class="loggedIn">
            <p>You are now connected to <strong class="user-name"><?php echo $user->getFullName()?> (<?php echo $user->getEmail()?>)</strong> account<br/>
                <a id="disconnect" class="disconnect-account" href="">Disconnect account</a></p>

            <?php if (!$isPremium):?>
                <div class="premium-panel">
                    <p class="premium-features">Premium features</p>
                    <button class="uilib-btn btn-upgrade upgrade">Upgrade</button>
                </div>
            <?php endif;?>
        </div>
    <?php endif;?>
</header>


<form id="settings-form">
    <input type="hidden" name="line_color" value="<?php echo $lineColor;?>"/>
    <input type="hidden" name="text_color" value="<?php echo $textColor;?>"/>
    <input type="hidden" name="bg_color" value="<?php echo $bgColor;?>"/>
    <input id="bg_opacity_value" type="hidden" name="bg_opacity" value="<?php echo $bgOpacity;?>"/>
    <input type="hidden" name="bg_is_transparent" value="<?php echo $bgIsTransparent;?>"/>
    <input type="hidden" name="is_show_cal_name" value="<?php echo $isShowCalName;?>"/>

<div class="accordion" wix-ctrl="Accordion">

    <div wix-scroll1="{height:446}">
        <div class="acc-pane">
            <h3>Calendar Setup: </h3>
            <div class="acc-content">
                <ul class="list">
                    <?php if ($user): $calsCount = count($cals);?>
                        <?php if ($calsCount === 0):?>
                            <div>
                                <button id="create-calendar" type="button" value="option1" class="uilib-btn btn-black-text">Start adding events!</button>
                            </div>
                        <?php elseif ($calsCount === 1): ?>
                            <input type="hidden" name="cal_id" value="<?php echo $cals[0]->getId();?>"/>

                            <li wix-label="<?php echo $cals[0]->getName();?>">
                                <a id="edit-calendar" href="#">Edit</a>
                            </li>
                        <?php else:?>
                            <input type="hidden" name="cal_id" value="<?php echo $calId;?>"/>
                            <li wix-label="Select calendar:">
                                <div wix-model="cal_id" wix-ctrl="Dropdown" wix-options="{width:'200', value:'<?php echo $calId;?>'}">
                                    <div value="">&nbsp;</div>
                                    <?php foreach ($cals as $cal):?>
                                    <div value="<?php echo $cal->getId();?>"><?php echo $cal->getName();?></div>
                                    <?php endforeach;?>
                                </div>
                                <a id="edit-calendar" href="">Edit</a>
                            </li>

                        <?php endif;?>

                        <?php if ($calsCount > 0):?>
                            <li wix-label="Show calendar name:">
                                <div wix-model="is_show_cal_name" wix-ctrl="Checkbox" wix-options="{checked:<?php echo ($isShowCalName ? 'true' : 'false')?>}"></div>
                            </li>
                        <?php endif;?>
                    <?php else:?>
                        <li>
                            <p>In order to start please first <a href="#" class="connect">connect your account</a></p>
                        </li>
                    <?php endif;?>
                </ul>
            </div>
        </div>

        <div class="acc-pane">
            <h3>Colors: </h3>
            <div class="acc-content">
                <ul class="list">
                    <li wix-label="Line Color:">
                        <div wix-model="line_color" wix-param1="line_color" wix-ctrl="ColorPicker"
                             wix-options="{startWithColor:'<?php echo $lineColor;?>'}"></div>
                    </li>

                    <li wix-label="Text Color:">
                        <div wix-model="text_color" wix-ctrl="ColorPicker"
                             wix-options="{startWithColor:'<?php echo $textColor;?>'}"></div>
                    </li>

                    <li wix-label="Background Color:">
                        <div wix-model="bg_color" wix-ctrl="ColorPickerWithOpacity" wix-options="{startWithColor:'<?php echo $bgColor;?>', startWithOpacity: <?php echo $bgOpacity * 100;?>}"></div>
                        <br/>
                        <div wix-model="bg_is_transparent" wix-ctrl="Checkbox" wix-options="{checked:<?php echo ($bgIsTransparent ? 'true' : 'false')?>, postLabel : 'Transparent'}"></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>

</form>


<script type="text/javascript">
    var BASE_URL = '<?php echo sfConfig::get('app_domain_full');?>';
    var INSTANCE = '<?php echo $instance;?>';
    var COMP_ID = '<?php echo $wix->getCompCode();?>';
    var CALS_COUNT = <?php echo $calsCount ? $calsCount : 0;?>
</script>

<!-- jQuery Dependency -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<!-- Wix SDK -->
<!--<script src="//sslstatic.wix.com/services/js-sdk/1.28.0/js/Wix.js"></script>-->
<script src="//sslstatic.wix.com/services/js-sdk/1.31.0/js/Wix.js"></script>

<script type="text/javascript" src="/bundle/wix-ui-lib-v2.1.6/dist/ui-lib.min.js"></script>
<script type="text/javascript" src="/js/wix/settings.js"></script>


</body>
</html>