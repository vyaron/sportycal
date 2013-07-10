<html>
<body style="background-color:#eee; padding-top: 50px; padding: 50px;">
<div style='width: 350px; color: #666px; background-color:#f2f2f2; font: normal 12px "Segoe UI", Arial, Sans-serif; -moz-border-radius: 7px; -webkit-border-radius: 7px; border-radius: 7px; border: 1px solid #b3b3b3; border-bottom-width: 2px; border-right-width: 2px; box-shadow:1px 1px 3px rgba(0,0,0,0.5); margin:0 auto; background-color:#fff;'>
  <div style="padding:10px;">
    <p><?php echo $message;?></p>
    
    <div> 
      <a style='width: 100px; height: 31px; line-height:31px; font-size:12px; float: left; display: block; margin-right: 7px; cursor: pointer; background: 0 bottom no-repeat url("<?php echo sfConfig::get('app_domain_full');?>/widgets/neverMiss/imgs/cal-link.png"); text-decoration: none; color: #333;'' target="_blank" href="<?php echo sfConfig::get('app_domain_full');?>/cal/sub/id/<?php echo $cal->getId();?>/ct/outlook/ref/widget/cal.ics">
      	<span style='padding-left: 33px; background: 0 0 no-repeat url("<?php echo sfConfig::get('app_domain_full');?>/widgets/neverMiss/imgs/cal-link.png"); display: block;'>Outlook</span>
      </a>
      
      <a style='width: 100px; height: 31px; line-height:31px; font-size:12px; float: left; display: block; margin-right: 7px; cursor: pointer; background: 0 bottom no-repeat url("<?php echo sfConfig::get('app_domain_full');?>/widgets/neverMiss/imgs/cal-link.png"); text-decoration: none; color: #333;'' target="_blank" href="<?php echo sfConfig::get('app_domain_full');?>/cal/sub/id/<?php echo $cal->getId();?>/ct/any/ref/widget/cal.ics">
      	<span style='padding-left: 33px; background: 0 -31px no-repeat url("<?php echo sfConfig::get('app_domain_full');?>/widgets/neverMiss/imgs/cal-link.png"); display: block;'>iCal</span>
      </a>
      <a style='width: 100px; height: 31px; line-height:31px; font-size:12px; float: left; display: block; margin-right: 7px; cursor: pointer; background: 0 bottom no-repeat url("<?php echo sfConfig::get('app_domain_full');?>/widgets/neverMiss/imgs/cal-link.png"); text-decoration: none; color: #333;'' target="_blank" href="<?php echo sfConfig::get('app_domain_full');?>/cal/sub/id/<?php echo $cal->getId();?>/ct/google/ref/widget/cal.ics">
      	<span style='padding-left: 33px; background: 0 -62px no-repeat url("<?php echo sfConfig::get('app_domain_full');?>/widgets/neverMiss/imgs/cal-link.png"); display: block;'>G Calendar</span>
      </a>
      
      <div style="clear:both;"></div>
    </div>
  </div>
</div>

<p style="text-align: center; font-size: 10px;">Created by <a href="<?php echo sfConfig::get('app_domain_full');?>" title="<?php echo sfConfig::get('app_domain_name');?> Website"><?php echo sfConfig::get('app_domain_name');?></a></p>

</body>
</html>