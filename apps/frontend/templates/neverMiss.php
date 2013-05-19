<?php
	use_helper('I18N');
	$userSession = sfContext::getInstance()->getUser();
	$culture = $userSession->getCulture();
	if ($culture === 'he_IL' || $culture === 'he') $RTL = true;
	else $RTL = false;
	
	$user = UserUtils::getLoggedIn();
?>


<!doctype html>
<html lang="en">
  <head>
    <title>Never Miss Website</title>
    <meta name="keywords" content="calendar, google calendar, outlook calendar, event, events, calendar wizard, calendar widget" />
    <meta name="description" content="Create follow calendar"/>
    <link rel="shortcut icon" href="/faviconNm.ico"/>
	<link href="/bundle/bootstrap/css/bootstrap.css" rel="stylesheet"/>
    <style type="text/css">
		body {
			padding-top: 60px;
			padding-bottom: 40px;
		}
		
		#footer {
			margin-top: 50px;
		}
	</style>
    <link href="/bundle/bootstrap/css/bootstrap-responsive.css" rel="stylesheet"/>
    <link href="/css/neverMiss/main.css" rel="stylesheet"/>
    <?php include_stylesheets() ?>
  </head>
  <body>
    <?php mb_internal_encoding('UTF-8');?>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="/">Never Miss</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/">Home</a></li>
              <?php if ($user):?>
              <li><a href="<?php echo url_for('main/logout') ?>"><?php echo __('Logout');?></a></li>
              <?php else:?>
              <li><a href="<?php echo url_for('partner/login');?>"><?php echo __('Login');?></a></li>
              <?php endif;?>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
		<?php echo $sf_content ?>
		
		<div id="footer">
	        <span style="color:gray">sportYcal &copy; 2010</span>&nbsp;&nbsp;|&nbsp;&nbsp;
	        <?php if ($user):?>
			<a href="<?php echo url_for('main/logout') ?>"><?php echo __('Logout');?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
			<?php else:?>
			<a href="<?php echo url_for('partner/login') ?>"><?php echo __('Login');?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
			<?php endif;?>
	        
	        <a href="<?php echo url_for('main/terms') ?>"><?php echo __('Terms & Conditions');?></a>
	    </div>
    </div>
	
	<script type="text/javascript" src="/bundle/jquery/js/jquery-1.9.1.min.js"></script>
	<?php include_javascripts()?>
    <script type="text/javascript">
		//Google Analytics
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-18494392-1']);
		_gaq.push(['_trackPageview']);
	      
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
  </body>
</html>
