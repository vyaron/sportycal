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
    
	use_helper('I18N');
	
	$userSession = sfContext::getInstance()->getUser();
	//$userSession->setCulture("he_IL");
	//$userSession->setCulture("en");
	
	$culture = $userSession->getCulture();
	if ($culture === 'he_IL' || $culture === 'he') $RTL = true;
	else $RTL = false;
	
	
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
    <meta name="description" 
          content="<?php echo $des ?>" />
    
    <link rel="shortcut icon" href="/favicon.ico" />
	<?php sfContext::getInstance()->getResponse()->addStylesheet('videobox/videobox.css'); ?>
	<?php sfContext::getInstance()->getResponse()->addStylesheet('datepicker/datepicker.css'); ?>	
	
	<?php if ($RTL):?>
	<?php sfContext::getInstance()->getResponse()->addStylesheet('rtl.css'); ?>	
	<?php endif;?>
	
	<?php sfContext::getInstance()->getResponse()->addJavascript('basics.js', 'first'); ?>
	
	<link href="/css/main.css" rel="stylesheet"/>
    <?php include_stylesheets() ?>
	
	


    <!--[if (lte IE 7)]>
    <style>
    .centererOuter{
        left:auto;
	float: none;
	position: static;
        
    }
    .centererInner{
        left:auto;
	float: none;
	position: static;
    }
    </style>
    <![endif]-->

    <style>
    .dontShow{
        display:none;        
    }
    </style>
    	<script type="text/javascript" src="/js/mootools-core.js"></script>
    	<script type="text/javascript" src="/js/mootools-more-1.4.0.1.js"></script>
    	<!-- script type="text/javascript" src="/js/videobox/extended.js"></script-->
    <script type="text/javascript">
    	var gRedirectAfterFbLogin = true;
    </script>    
    
    <?php if (! UserUtils::getUserTZ()): //Get User Timezone by JS?>
	<script type="text/javascript" src="/js/getUserTZ.js"></script>
	<?php endif;?>
  </head>
  <body>

    <?php
    mb_internal_encoding('UTF-8');
    $user = UserUtils::getLoggedIn();
    
    ?>
    <?php if (false) :?>
    <div class="dontShow">
        <h1> Download Sport Schedules into your Calendar - <?php include_slot('keywords') ?> </h1>
    </div>
    <?php endif ?>
    <div id="header">
        <div id="headerLeft"> 
            <a id="logo" href="<?php echo url_for('main/index') ?>">
                <?php echo image_tag('layout/logo.gif', 'id="imgLogo" alt="sportYcal Logo"')?>    
            </a>
            
            <!-- Video Link -->
            <a href="http://www.youtube.com/watch?v=h8MS6PPNke0" rel="vidbox 640 390" title="sportYcal video!" id="watchDemoMovie">
            	<?php if ($isHomePage || $isSearchPage) :?>
            		<div class="c b" style="">
						<?php echo image_tag('layout/play.png', 'alt="Play sportYcal Video"')?>
						<br/>
            			<span style="font-size: 14px;"><?php echo __('Watch the Video');?></span>
            		</div>
            	<?php else :?>
	            	<?php echo image_tag('layout/playSmall.png', 'alt="Play sportYcal Video"')?>
					<?php echo __('Watch the Video');?>	            	
            	<?php endif ?>
            </a>
            <div class="cb"></div>
        </div>

        <div id="topNav">
        	<div>
	            <ul>
	                <li class="topNavItem">
	                    <?php if (!$user) : ?>
	                    	<div class="fbLogin fl" id="fbLogin"><div><?php echo __('Log In');?></div></div>    
	                    <?php else: ?>
	                        <?php echo $user->getFullName()?>
	                        </li>
	                        <li class="topNavItem">
	                            <a href="<?php echo url_for('main/logout') ?>" onclick="doLogout();return false;"><?php echo __('Logout');?></a>
	                    <?php endif; ?>
	                </li>
	
	                <li class="topNavItem">
	                    <a href="<?php echo url_for('main/about') ?>"><?php echo __('About Us');?></a>
	                </li>
	                <li class="topNavItem" style="border:0px;">
	                    <a href="<?php echo url_for('main/contact') ?>"><?php echo __('Contact Us');?></a>
	                </li>
		
	            </ul>
	            <div class="cb"></div>
			</div>
			
            <div style="text-align:left;margin-top:8px;margin-left:8px;">
            	<!-- Search -->
	            <?php if (!$isSearchPage):?>
	            	<?php include_partial('global/headerSearch'/*, array('txtSearch' => $txtSearch, 'fromDate' => $fromDate, 'toDate' => $toDate)*/) ?>
	            <?php endif;?>
	            
	            <?php if ($user && $user->isMaster()) :?>
	            	<?php include_partial('global/changeLang');?>
	            <?php endif;?>
	            <div class="cb"></div>
			</div>            
            <!--div id="siteSlogan">All sport calendars you need</div-->
            
        </div>
		<div class="cb"></div>
    </div>
    
    <div id="content">
        
    
        <div id="divContent">
            <?php echo $sf_content ?>
        </div>

	<div class="cb"></div>
    <div id="footer">
        <a href="<?php echo url_for('main/terms') ?>"><?php echo __('Terms & Conditions');?></a> &nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="<?php echo url_for('main/about') ?>"><?php echo __('About Us');?></a> &nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="<?php echo url_for('main/contact') ?>"><?php echo __('Contact Us');?></a> &nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="http://www.youtube.com/watch?v=h8MS6PPNke0" rel="vidbox 640 390" title="sportYcal">
         	<?php echo image_tag('layout/playSmall.png', 'alt="Play sportYcal Video"')?>
           	<?php echo __('Watch the Video');?>
        </a>&nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="<?php echo url_for('main/friendsBirthday?src=site') ?>"><?php echo __('Birthdays Calendar');?></a>
        
		<?php if ($user && $user->isMaster()) :?>
		        &nbsp;&nbsp;|&nbsp;&nbsp;
		        <a href="<?php echo url_for('main/promo?src=site') ?>"><?php echo __('Get an iPad');?></a> 
		<?php endif ?>

        
        <?php if (IS_MOBILE):?>
         &nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="/?fullSite=0"><?php echo __('Mobile Site');?></a>
		<?php endif;?>
		
        <br/><br/>
        <span style="color:gray">sportYcal &copy; 2010</span>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="<?php echo url_for('partner/login') ?>"><?php echo __('Partners Login');?></a>
        
        <?php if ($user && $user->isPartner()) :?>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="<?php echo url_for('admin/partnerReport') ?>"><?php echo __('Partners Reports');?></a> 
        <?php endif;?>
        
        <?php if ($user && $user->isMaster()) :?>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="<?php echo url_for('admin/partnersReports') ?>"><?php echo __('Partners Reports');?></a> 
        <?php endif;?>
        
        <?php if ($isAboutPage) :?>
	        <br/><br/>
	        <span style="color:gray"><?php echo __('Design by Hovav Sadan: hovavnowalla@gmail.com');?></span>
		<?php endif ?>
        <?php if ($user && $user->isMaster()) :?>

        <div class="adminLinks">
            <br/><br/><br/>
            <a href="<?php echo url_for('admin/index') ?>"><?php echo __('Master Page');?></a>
        </div>
        
        <?php endif;?>
    </div>


    </div>
    
	<?php if ($user && $user->isMaster()) :?>
	<a id="iPadBanner" href="<?php echo url_for('main/promo?src=site') ?>"></a>
	<?php endif ?>

	<?php if (true) :?>
	<div id="friendBirthdayBannerWrapper">
		<a id="closeFriendBirthdayBanner" href="#"></a>
		<a id="friendBirthdayBanner" href="<?php echo url_for('main/friendsBirthday?src=site') ?>"></a>
		<div class="cb"></div>
	</div>
	<?php endif?>
	
	<?php if (!$isContactPage):?>
		<?php include_partial('global/feedback', array('user' => $user)); ?>
	<?php endif;?>
	
	<div id="ajaxSearch" class="ajaxSearch hidden"></div>

	<?php include_javascripts() ?>
	<script type="text/javascript" src="/js/videobox/swfobject.js"></script>
	<script type="text/javascript" src="/js/videobox/videobox.js"></script>

    <div id="fb-root"></div>
    <script type="text/javascript">
    	//Facebook
	    window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '<?php echo FACEBOOK_APP_ID ?>', // App ID
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
	    
		function doLogout() {
			if (FB.getSession()) {
				FB.logout(doAfterLogout);
			} else {
				doAfterLogout();
			}
		}
	    
		function doAfterLogout() {
			window.location = '/main/logout';
		}

		//Google Analytics
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-18494392-1']);
		_gaq.push(['_trackPageview']);
	      
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
		
		window.addEvent('domready', function() {
			//facebook login
			var fbLogin = $('fbLogin');
			if (fbLogin){
				fbLogin.addEvent('click', function(e){
					e.stop();
					FB.login(function(response) {
						if (response.authResponse) {
							doAfterFbLogin();
						}
					}, {scope: 'email,user_birthday'});
				});
			}

			var changeLang = $('changeLang');
			if (changeLang){
				changeLang.addEvent('change', function(){
					var req = new Request({
						url : '/main/dummy',
						onComplete : function(){
							window.location.reload();
						}
					}).post('lang=' + changeLang.get('value'));
				});
			}
			
		    //alert('The DOM is ready!');
			if (isUserWatchedMovie()) {
				//$('watchDemoMovie').hide();
				$('watchDemoMovie').setStyle('display', 'none');
			}
			
			$('watchDemoMovie').addEvent('click', setWatchedMovieCookie);
			
			// Banner - promotion iPad
			var iPadBanner = $('iPadBanner');
			if (iPadBanner){
				if (isUserClickOnPromotionBanner()){
					iPadBanner.addClass('hidden');
				}
				iPadBanner.addEvent('click', setPromotionBannerCookie);
			}
		
			//Friends Birthday banner
			
			
			var friendBirthdayBanner = $('friendBirthdayBanner');
			if (friendBirthdayBanner){
				if (isUserClickOnFriendBirthdayBanner()){
					hideFriendsBdBanner();
				}
				friendBirthdayBanner.addEvent('click', setFriendBirthdayBannerCookie);
			}

			var closeFriendBirthdayBanner = $('closeFriendBirthdayBanner');
			if (closeFriendBirthdayBanner){
				closeFriendBirthdayBanner.addEvent('click', setFriendBirthdayBannerCookie);
			}
		});
	</script>
  </body>
</html>

