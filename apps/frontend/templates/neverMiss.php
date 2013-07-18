<?php
	use_helper('I18N');
	$userSession = sfContext::getInstance()->getUser();
	$culture = $userSession->getCulture();
	if ($culture === 'he_IL' || $culture === 'he') $RTL = true;
	else $RTL = false;
	
	$user = UserUtils::getLoggedIn();
?>


<!DOCTYPE HTML>
<html lang="en">
  <head>
  	<meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="keywords" content="calendar, google calendar, outlook calendar, event, events, calendar wizard, calendar widget" />
    <meta name="description" content="Create follow calendar"/>
    
    <title><?php echo sfConfig::get('app_domain_name');?> Website</title>
    
    <link rel="shortcut icon" href="/faviconNm.ico"/>
	<link href="/bundle/bootstrap/css/bootstrap.css" rel="stylesheet"/>
    <link href="/bundle/bootstrap/css/bootstrap-responsive.css" rel="stylesheet"/>
    <link href="/css/neverMiss/main.css" rel="stylesheet"/>
    <?php include_stylesheets() ?>
  </head>
  <body>
    <?php mb_internal_encoding('UTF-8');?>
    
    <div id="container">
		<div id="top-navbar-placholder">&nbsp;</div>
	    <div class="navbar navbar-inverse navbar-fixed-top">
	      <div class="navbar-inner">
	        <div class="container">
	          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <a id="logo" href="/" title="<?php echo sfConfig::get('app_domain_name');?> Website">&nbsp;</a>
	          <div class="nav-collapse collapse">
	          	<div id="top-nav-wrapper">
		        <?php include_partial('global/topNav', array('user' => $user)); ?>
	            </div>
	          </div>
	        </div>
	      </div>
	    </div>
	
	    <div class="container">
			<div id="alerts"></div>
		</div>
		
	    
		<?php echo $sf_content ?>
	
		<div id="footer-place-hoolder">&nbsp;</div>
	    <div id="footer">
	    	<div class="container">
	    		<span style="color:gray"><?php echo sfConfig::get('app_domain_name');?> &copy; <?php echo date('Y');?></span>&nbsp;&nbsp;|&nbsp;&nbsp;
		        <?php if ($user):?>
				<a href="<?php echo url_for('main/logout') ?>"><?php echo __('Sign Out');?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
				<?php else:?>
				<a href="<?php echo url_for('partner/login') ?>"><?php echo __('Log In');?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
				<?php endif;?>
		        
		        <a href="<?php echo url_for('nm/pricing') ?>">Pricing</a>&nbsp;&nbsp;|&nbsp;&nbsp;
		        <a href="<?php echo url_for('nm/caseStudies') ?>">Case Studies</a>&nbsp;&nbsp;|&nbsp;&nbsp;
		        <a href="<?php echo url_for('nm/terms') ?>"><?php echo __('Terms & Conditions');?></a>
	    	</div>
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
	<script type="text/javascript" src="/bundle/jquery/js/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="/js/neverMiss/basic.js"></script>
	<script type="text/javascript" src="/bundle/bootstrap/js/bootstrap.min.js"></script>
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
