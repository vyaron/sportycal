<?php echo $message;?>

Outlook: <?php echo sfConfig::get('app_domain_full');?>/cal/sub/id/<?php echo $cal->getId();?>/ct/outlook/ref/widget/cal.ics
iCal: <?php echo sfConfig::get('app_domain_full');?>/cal/sub/id/<?php echo $cal->getId();?>/ct/any/ref/widget/cal.ic
G Calendar: <?php echo sfConfig::get('app_domain_full');?>/cal/sub/id/<?php echo $cal->getId();?>/ct/google/ref/widget/cal.ics

Created by <?php echo sfConfig::get('app_domain_name');?>: <?php echo sfConfig::get('app_domain_full')?>