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


<!doctype html>
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
	
	<link href="/bundle/bootstrap/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
		body {
			padding-top: 60px;
			padding-bottom: 40px;
		}
		
		#footer {
			margin-top: 50px;
		}
</style>
    <link href="/bundle/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <?php //include_stylesheets() ?> 
    <script type="text/javascript">
		var IS_USER_LOGED_IN = <?php echo ($user) ? 'true' : 'false';?>
    </script>
  </head>
  <body>
    <?php mb_internal_encoding('UTF-8'); $user = UserUtils::getLoggedIn();?>
    
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">Never Miss</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/">Home</a></li>
              <?php if ($user):?>
              <li><a href="<?php echo url_for('main/logout') ?>"><?php echo __('Logout');?></a></li>
              <?php else:?>
              <li><a href="<?php echo url_for('partner/login');?>"><?php echo __('Login');?></a></li>
              <?php endif;?>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
		<?php echo $sf_content ?>
		
		<div id="footer">
	        <span style="color:gray">sportYcal &copy; 2010</span>
	        &nbsp;&nbsp;|&nbsp;&nbsp;
	        <a href="<?php echo url_for('partner/login') ?>"><?php echo __('Login');?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
	        
	        <?php if ($user && $user->isPartner()) :?>
	        &nbsp;&nbsp;|&nbsp;&nbsp;
	        <a href="<?php echo url_for('admin/partnerReport') ?>"><?php echo __('Partners Reports');?></a> &nbsp;&nbsp;|&nbsp;&nbsp;
	        <?php endif;?>
	        
	        <?php if ($user && $user->isMaster()) :?>
	        &nbsp;&nbsp;|&nbsp;&nbsp;
	        <a href="<?php echo url_for('admin/partnersReports') ?>"><?php echo __('Partners Reports');?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
	        <?php endif;?>
	        
	        <a href="<?php echo url_for('main/terms') ?>"><?php echo __('Terms & Conditions');?></a>
	        
	        <?php if ($isAboutPage) :?>
		        <br/><br/>
		        <span style="color:gray"><?php echo __('Design by Hovav Sadan: hovavnowalla@gmail.com');?></span>
			<?php endif ?>
	        <?php if ($user && $user->isMaster()) :?>
			
	        <div class="adminLinks">
	            <br/><br/><br/>
	            <a href="<?php echo url_for('admin/index') ?>"><?php echo __('Master Page');?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
	        </div>
	        
	        <?php endif;?>
	    </div>
    </div>
	
	<?php //include_javascripts() ?>
	<!-- 
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
	 -->
  </body>
</html>
