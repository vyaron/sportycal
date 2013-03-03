<?php sfContext::getInstance()->getResponse()->addJavascript('basics.js'); ?>
<div class="adminLinks">

	(
	<?php if (!$cal->hasEventsDescription()):?>
	<a id="showCalPreview" href="#" class="mobilePopup" itemId="0">Preview</a>&nbsp;|&nbsp;
	<?php endif;?>
	<a href="<?php echo url_for('cal/edit?id='.$cal->getId()) ?>">Edit this Calendar</a>
	&nbsp;|&nbsp;
	<?php echo link_to('Delete this Calendar', 'cal/delete?id='.$cal->getId(), array('method' => 'delete', 'confirm' => 'DELETE this CALENDAR?')) ?>
	&nbsp;|&nbsp;
	<a href="<?php echo url_for('event/new?calId='.$cal->getId()) ?>">Add an Event</a>
	<?php if (false) :?>
	&nbsp;|&nbsp;
	<a href="javascript:toggleIt('calPartnersDesc', true)">Partner Descs [<?php echo count($partnersDesc)?>]</a>)
	<?php endif ?>
    )
    
    <div id="calPartnersDesc" style="display:none;">
        <table cellspacing="15px">
          <thead>
            <tr>
              <th>partner id</th>
              <th>partner name</th>
              <th>Desc</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
        
            <?php foreach ($partnersDesc as $partnerDesc): ?>
            <tr>
              <td><?php echo $partnerDesc->getPartnerId() ?></td>
              <td><?php echo $partnerDesc->getPartner()->getName() ?></td>
              <td><?php echo $partnerDesc->getDescription() ?></td>
              <td><a href="<?php echo url_for('cal/deletePartnerDesc?pdId='.$partnerDesc->getId().'&calId='.$cal->getId()) ?>">Delete</a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <a href="javascript:toggleIt('frmAdd', true)">Add Partner Desc</a>
        <div id="frmAdd" style="display:none;">
            <form action="<?php echo url_for('cal/updatePartnerDesc')?>" method="post">
                <input type="hidden" name="calId" value="<?php echo $cal->getId()?>" />
                Partner: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <select name="partnerId">
                	<?php foreach ($partners as $partner) :?>
                		<option value="<?php echo $partner->getId()?>"><?php echo $partner->getName()?></option>
                	<?php endforeach ?>
                </select><br/>
                Description: <textarea id="partnerDesc" name="partnerDesc" ></textarea>
                <input type="submit" value="Carefully Add" />
            </form>
        </div>
  
    </div>

<script type="text/javascript">
</script>

    
</div>

    
