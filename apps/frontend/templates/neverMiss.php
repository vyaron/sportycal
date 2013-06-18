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
              <li<?php echo has_slot('homepage') ? ' class="active"' : '';?>><a href="/">Home</a></li>
              <?php if ($user):?>
              <li<?php echo has_slot('calList') ? ' class="active"' : '';?>><a href="<?php echo url_for('nm/calList') ?>">Calendars</a></li>
              <li><a href="<?php echo url_for('main/logout') ?>"><?php echo __('Logout');?></a></li>
              <?php else:?>
              <li<?php echo has_slot('login') ? ' class="active"' : '';?>><a href="<?php echo url_for('partner/login');?>"><?php echo __('Login');?></a></li>
              <?php endif;?>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
		<div id="alerts"></div>

        
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
	
	<div style="display:none;">
		<div id="DUMMY_GLOBAL_ERROR">
			<div class="alert alert-block alert-error">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				{CONTENT}
			</div>
		</div>
		<div id="DUMMY_GLOBAL_SUCCESS">
			<div class="alert alert-block alert-success">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				{CONTENT}
			</div>
		</div>
	</div>
	
	
	<div id="fb-root"></div>
	<script type="text/javascript" src="/bundle/jquery/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="/js/neverMiss/basic.js"></script>
	<?php include_javascripts()?>
    <script type="text/javascript">
	  	//Facebook
	    window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '<?php echo sfConfig::get('app_facebook_appId');?>', // App ID
		      channelURL : '<?php echo sfConfig::get('app_domain_short')?>/channel.php', // Channel File
		      status     : true, // check login status
		      cookie     : true, // enable cookies to allow the server to access the session
		      oauth      : true, // enable OAuth 2.0
		      xfbml      : true  // parse XFBML
		    });
	    };
	
	 	// Load the SDK Asynchronously
		(function(d){
	 		var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
	 		js = d.createElement('script'); js.id = id; js.async = true;
	 		js.src = "//connect.facebook.net/en_US/all.js";
	 		d.getElementsByTagName('head')[0].appendChild(js);
		}(document));
    
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
