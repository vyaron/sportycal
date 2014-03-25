<?php
    use_helper('I18N');

    $userSession = sfContext::getInstance()->getUser();
    $culture = $userSession->getCulture();
    if ($culture === 'he_IL' || $culture === 'he') $RTL = true;
    else $RTL = false;

    //$RTL = true;

    $keywords  = "Add to Calendar,";
    if (has_slot('keywords')) {
        $keywords .= get_slot('keywords');
    }

    $des  = "";
    if (has_slot('title')) {
        $des .= get_slot('title') . " - ";
    }
    $des .= "To your own Calendar: Outlook, Google Calendar, Apple Calendar, Mobile Calendar, etc. Subscribe and be in sync with the schedule so you wont miss an event";


?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8"/>

    <title>
        iNeverMiss -
        <?php if (!include_slot('title')): ?>
            Right into your own Calendar
        <?php endif; ?>
    </title>
    <meta name="keywords" content="<?php echo $keywords ?>" />
    <meta name="description"   content="<?php echo $des ?>" />


	<link type="text/css" href="/bundle/normalize/normalize.css" rel="stylesheet"/>
    <?php if ($RTL):?>
        <?php sfContext::getInstance()->getResponse()->addStylesheet('rtl.css'); ?>
    <?php endif;?>
    <?php include_stylesheets() ?>

</head>
<body>

<?php echo $sf_content ?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/bundle/jquery/js/jquery-1.10.2.min.js"><\/script>');</script>

<?php include_javascripts()?>
</body>
</html> 