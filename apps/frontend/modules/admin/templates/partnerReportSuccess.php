<?php 
sfContext::getInstance()->getResponse()->addStylesheet('partnerReport.css');
$user = UserUtils::getLoggedIn();
?>

<?php if ($user && $user->isMaster()):?>
<h3 class="backToPartnerReportLink"><a href='<?php echo url_for("admin/partnersReports")?>'> &lt; Back to Partners Reports</a></h3>
<?php endif;?>

<?php if (count($cals)):?>
<form class="partnerReport" action="/admin/partnerCalReport">
	<?php if ($user && $user->isMaster()):?>
	<input type="hidden" name="partnerId" value="<?php echo $partnerId;?>"/>
	<?php endif;?>
	<div class="partnerReportPadding">
		<h5>Select your calendar:</h5>
		<select name="calId">
			<option value="">Please Choose</option>
			<?php foreach ($cals as $cal):?>
			<option value="<?php echo $cal->getId()?>"><?php echo $cal->getName();?></option>
			<?php endforeach;?>
		</select>
		<input type="submit" value="Show Reports">
	</div>
</form>
<?php endif;?>

<?php if($shortUrls):?>
	<table class="partnerReport partnerReportTable" style="width:900px;">
		<thead>
			<tr>
				<th>Full URL</th>
				<th>Short URL</th>
				<th>Desc</th>
				<th>Used At</th>
				<th>Count Used</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($shortUrls as $shortUrl):?>
		<tr>
			<td><a href="<?php echo $shortUrl->getUrl();?>" target="_blank"><?php echo $shortUrl->getUrl();?></a></td>
			<td><a href="<?php echo sfConfig::get('app_domain_full') . '/l/' . $shortUrl->getHash();?>" target="_blank"><?php echo sfConfig::get('app_domain_full') . '/l/' . $shortUrl->getHash();?></a></td>
			<td><?php echo $shortUrl->getComment();?></td>
			<td><?php echo $shortUrl->getUsedAt();?></td>
			<td><?php echo $shortUrl->getCountUsed();?></td>
		</tr>
		<?php endforeach;?>
		</tbody>
	</table>
<?php endif;?>

<?php if ($generalDetails):?>
<div style="padding-bottom:30px;">
	<p class="generalInfo"><span class="b">Total Downloads:</span> <?php echo $generalDetails->num_user_cal;?></p>
	<p class="generalInfo"><span class="b">Last download:</span> <?php echo $generalDetails->getTakenAt();?></p>
</div>
<?php endif;?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<?php if ($calReportByDaysCount = count($calReportByDays)):?>
<div id="calReportByDays" class="googleChart"></div>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(function() {
		var data = google.visualization.arrayToDataTable([
			['Day', 'Download'],
			<?php foreach ($calReportByDays as $i => $calReportByDay):?>
			['<?php echo date('Y-m-d', strtotime($calReportByDay->getTakenAt()))?>', <?php echo $calReportByDay->num_user_cal;?>]<?php echo ($calReportByDaysCount != $i -1) ? ',' :''?>
	        <?php endforeach;?>
        ]);

        var options = {
          title: 'Downloads per day'
        };

        var chart = new google.visualization.LineChart(document.getElementById('calReportByDays'));
        chart.draw(data, options);
	});
</script>
<?php endif;?>

<?php if ($calReportByTypesCount = count($calReportByTypes)):?>
<div id="calReportByTypes" class="googleChart"></div>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(function () {
		var data = google.visualization.arrayToDataTable([
			['Type', 'Download'],
			<?php foreach ($calReportByTypes as $i => $calReportByType):?>
			['<?php echo $calReportByType->getCalType();?>', <?php echo $calReportByType->num_user_cal;?>]<?php echo ($calReportByTypesCount != $i -1) ? ',' :''?>
			<?php endforeach;?>
		]);

        var options = {
          title: 'Downloads per type'
        };

        var chart = new google.visualization.PieChart(document.getElementById('calReportByTypes'));
        chart.draw(data, options);
      });
  </script>
<?php endif;?>


