<?php 
sfContext::getInstance()->getResponse()->addStylesheet('partnerReport.css');
$user = UserUtils::getLoggedIn();
?>

<?php if ($user && $user->isMaster()):?>
<h3 class="backToPartnerReportLink"><a href='<?php echo url_for("admin/partnerReport") . '?partnerId=' . $partnerId?>'> &lt; Back to Partner Report</a></h3>
<?php else:?>
<h3 class="backToPartnerReportLink"><a href='<?php echo url_for("admin/partnerReport")?>'> &lt; Back to Partner Report</a></h3>
<?php endif;?>

<table class="partnerReportTable">
	<tr ><th colspan="2" class="partnerReportTableTitle">Cal downloads by day</th></tr>
	<tr><th>Date</th><th>Count</th></tr>
	<?php foreach ($reportByDays as $reportByDay):?>
		<tr>
			<td><?php echo $reportByDay->getTakenAt();?></td>
			<td><?php echo $reportByDay->num_user_cal;?></td>
		</tr>
	<?php endforeach;?>
</table>

<table class="partnerReportTable">
	<tr ><th colspan="2" class="partnerReportTableTitle">Cal downloads by type</th></tr>
	<tr><th>Type</th><th>Count</th></tr>
	<?php foreach ($reportByTypes as $reportByType):?>
		<tr>
			<td><?php echo $reportByType->getCalType();?></td>
			<td><?php echo $reportByType->num_user_cal;?></td>
		</tr>
	<?php endforeach;?>
</table>

<?php if (false):?> 
<table class="partnerReportTable">
	<tr ><th colspan="2" class="partnerReportTableTitle">Open events</th></tr>
	<tr><th>Event_Id</th><th>IP Address</th></tr>
	<?php foreach ($reportByActionTypes as $reportByActionType):?>
		<tr>
			<td><?php echo $reportByActionType->getEventId();?></td>
			<td><?php echo $reportByActionType->getIpAddress();?></td>
		</tr>
	<?php endforeach;?>
</table>
<?php endif;?>

<?php if ($user && $user->isMaster()):?>
<h3 class="backToPartnerReportLink"><a href='<?php echo url_for("admin/partnerReport") . '?partnerId=' . $partnerId?>'> &lt; Back to Partner Report</a></h3>
<?php else:?>
<h3 class="backToPartnerReportLink"><a href='<?php echo url_for("admin/partnerReport")?>'> &lt; Back to Partner Report</a></h3>
<?php endif;?>