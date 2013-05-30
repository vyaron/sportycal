<?php
    
    // Handle Keywords and Description
    $keywords  = "sport calendar,sport calendars,";
    if (has_slot('keywords')) {
    	$keywords .= get_slot('keywords');
    } else {
	    $keywords .= "sports calendar, sports calendars, sport schedule,sport schedules,my calendar,to google calendar,to outlook calendar,to mobile calendar,to iphone calendar,to android calendar,to ical,sportcal,sportical";
    } 

    $des  = "Download Sport Schedules to your Calendar - ";
    if (has_slot('title')) {
    	$des .= get_slot('title') . " - ";
    }
	$des .= "sportYcal gathers sport events from all around the world and lets you download your chosen events right into your calendar";
    
?>


<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" lang="en" class="no-js">
	<head>
		<meta charset="utf-8">
		<meta name="HandheldFriendly" content="true" />
		<meta name="MobileOptimized" content="320" />
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0"  />				
		
		<?//php include_http_metas() ?>
	    <?//php include_metas() ?>
	    
		<meta name="keywords" content="<?php echo $keywords ?>" />
	    <meta name="description" content="<?php echo $des ?>" />
	    <link rel="shortcut icon" href="/favicon.ico" />
	
	  	<title>sportYcal - Download Sports Schedules - <?php if (!include_slot('title')): ?> Right into your own Calendar <?php endif; ?></title>
	  	
		<link href="/css/mobile/main.css" rel="stylesheet" type="text/css" media="screen, projection">
		<link href="/css/mobile/mobile.css" rel="stylesheet" type="text/css" media="all" />
		
		<script type="text/javascript" src="/js/mootools-core.js"></script>
		
		<?php if (! UserUtils::getUserTZ()): //Get User Timezone by JS?>
		<script type="text/javascript" src="/js/getUserTZ.js"></script>
		<?php endif;?>
	</head>
	<body>
	    <?php
	    	mb_internal_encoding('UTF-8');
	    	$user = UserUtils::getLoggedIn();
	    ?>

	    <!-- Content -->
	    <section id="content">
    		<?php echo $sf_content ?>
    	</section>
			
    	<!-- footer -->
    	<footer id="pageFooter">
    		<!-- nav -->
    		<section id="quickNavWrapper">
    			 <ul id="quickNav">
    			 	<li><a href="javascript:scroll(0,0)">Top</a> | </li>
    			 	<li><a href="<?php echo url_for('main/terms') ?>" target="_blank">Terms &amp; Conditions</a> | </li>
    			 	<li><a href="<?php echo url_for('main/about') ?>" target="_blank">About Us</a> | </li>
    			 	<li><a href="<?php echo url_for('main/contact') ?>" target="_blank">Contact Us</a> | </li>
    			 	<li><a href="<?php echo url_for('main/friendsBirthday/src/mobile') ?>" target="_blank">Birthdays Calendar</a> | </li>
    			 	<li><a href="/?fullSite=1">Full Site</a></li>
    			 </ul>
    		</section>
    		
    		<!-- Copyrights -->
    		<section id="companyInfo">
    			<p id="copyRights">sportYcal &copy; <?php echo date('Y')?></p>
    		</section>
    		
    		<?php if ($user && $user->isMaster()) :?>
    		<!-- Admin link -->
	        <div class="adminLinks">
	            <br/><br/><br/>
	            <a href="<?php echo url_for('admin/index') ?>">Master Page</a>
	        </div>
	        <?php endif;?>
    	</footer>

	<?php include_javascripts() ?>
	<div id="fb-root"></div>
	<script src="http://connect.facebook.net/en_US/all.js"></script>
    <script type="text/javascript">
	  	//Facebook
	    window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '<?php echo sfConfig::get('app_facebook_appId');?>', // App ID
		      channelURL : 'sportycal.com/channel.php', // Channel File
		      status     : true, // check login status
		      cookie     : true, // enable cookies to allow the server to access the session
		      oauth      : true, // enable OAuth 2.0
		      xfbml      : true  // parse XFBML
		    });
	
		 	// whenever the user logs in, we refresh the page
	      	FB.Event.subscribe('auth.login', doAfterFbLogin);
	    };
	
	 	// Load the SDK Asynchronously
		(function(d){
	 		var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
	 		js = d.createElement('script'); js.id = id; js.async = true;
	 		js.src = "//connect.facebook.net/en_US/all.js";
	 		d.getElementsByTagName('head')[0].appendChild(js);
			}(document));
	
		function doAfterFbLogin(){
			var currLocation = window.location; 
	        if (typeof gDoNotRedirectAfterFbLogin == 'undefined'){
	        	window.location = "/main/fbLogin?gt=" + encodeURI(window.location);
			}
		}

    	//Google analytics
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

