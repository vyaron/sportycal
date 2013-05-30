<?php
	$module 		= $sf_request->getParameter('module');
    $action 		= $sf_request->getParameter('action');
    $isHomePage 	= ($module == 'main' && $action == 'index');
    $isSearchPage 	= ($module == 'main' && $action == 'search');
    $isAboutPage 	= ($module == 'main' && $action == 'about');
    $isContactPage 	= ($module == 'main' && $action == 'contact');
    
    
    // Handle Keywords and Description
    $keywords  = "sport calendar,team calendar,ical sports,";
    if (has_slot('keywords')) {
    	$keywords .= get_slot('keywords');
    } else {
	    $keywords .= "sport calendars, sports calendar, sports calendars, sport schedule,sport schedules,ical, team schedule, team calendar, my calendar,to google calendar,to outlook calendar,to mobile calendar,to iphone calendar,to android calendar,to ical,sportcal,sportical";
    } 

    $des  = "Download Sports Schedules - ";
    if (has_slot('title')) {
    	$des .= get_slot('title') . " - ";
    }
	$des .= "To your own Calendar: Outlook, Google Calendar, Apple Calendar, etc. Subscribe to your favourite sport team / sport tournaments and be synched with their schedule so you never miss a game again";
    
	sfContext::getInstance()->getResponse()->addStylesheet('fbApp.css');
	
	use_helper('I18N');
	
	$userSession = sfContext::getInstance()->getUser();
	$culture = $userSession->getCulture();
	if ($culture === 'he_IL' || $culture === 'he') sfContext::getInstance()->getResponse()->addStylesheet('rtl.css');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="en" lang="en">
  <head>
    	<?php include_http_metas() ?>
    	<?php include_metas() ?>
    	
    	<!-- facebook -->
	    <?php if (has_slot('og:description')): ?>
	    <meta property="og:description" content="<?php echo !include_slot('og:description')?>" />
	    <?php endif;?>
    	
	    <title>
	        sportYcal - Sports Schedules - 
	        <?php if (!include_slot('title')): ?>
	            Right into your own Calendar
	          <?php endif; ?>
	    </title>
   		<meta name="keywords" content="<?php echo $keywords ?>" />
    	<meta name="description" content="<?php echo $des ?>" />
    	<link rel="shortcut icon" href="/favicon.ico" />
		<?php sfContext::getInstance()->getResponse()->addJavascript('basics.js', 'first'); ?>
    	<?php include_stylesheets() ?>
    	<script type="text/javascript" src="/js/mootools-core.js"></script> 
  </head>
  <body>
    <?php mb_internal_encoding('UTF-8'); ?>
    
    <div id="content">
        <div id="divContent">
            <?php echo $sf_content ?>
        </div>
	</div>
	

	<div id="fb-root"></div>
	<?php include_javascripts() ?>
	 <script type="text/javascript">
		//Facebook
	    window.fbAsyncInit = function() {
			FB.init({
				appId      : '<?php echo sfConfig::get('app_facebook_appId');?>', // App ID
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

		
		window.addEvent('domready', function(){
			if (top == self && window.location.href.indexOf('fb=')<0) {
				var myRequest = new Request({
					url : '/main/notInIframe',
					async : false,
					onComplete : function(){
						window.location.reload();
					}
				}).post();
			}
		});
	</script>
  </body>
</html>

