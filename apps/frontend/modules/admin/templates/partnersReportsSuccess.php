<?php sfContext::getInstance()->getResponse()->addStylesheet('partnerReport.css');?>

<form class="partnerReport" action="/admin/partnerReport">
	<div class="partnerReportPadding">
		<h5>Select partner:</h5>
		<select name="partnerId">
			<?php foreach ($partners as $partner):?>
			<option value="<?php echo $partner->getId()?>"><?php echo $partner->getName();?></option>
			<?php endforeach;?>
		</select>
		<input type="submit" value="Show Reports">
	</div>
</form>
