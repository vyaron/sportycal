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
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.1.2/css/bootstrap-rtl.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.1.0/animate.min.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">

    <?php if ($RTL):?>
        <?php sfContext::getInstance()->getResponse()->addStylesheet('rtl.css'); ?>
    <?php endif;?>
    <?php include_stylesheets() ?>

</head>
<body>

<?php echo $sf_content ?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/bundle/jquery/js/jquery-1.10.2.min.js"><\/script>');</script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/redmond/jquery-ui.min.css" />


<?php include_javascripts()?>
</body>
</html> 