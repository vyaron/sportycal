<h2>Calendars</h2>

<?php if (!count($cals)): ?>
<p>No calendars!</p>
<?php else:?>
	<?php foreach ($cals as $cal):?>
	<div class="row-fluid">
		<div class="span4"><?php echo $cal->getName();?></div>
		<div class="span4">
			<a class="btn btn-mini" href="<?php echo url_for('nm/calEdit?id=' . $cal->getId());?>"><i class="icon-pencil"></i> Edit</a>
			<a class="btn btn-mini delete-cal" href="<?php echo url_for('nm/calDelete?id=' . $cal->getId());?>"><i class="icon-trash"></i> Delete</a>
		</div>
	</div>	
	<?php endforeach;?>
<?php endif;?>
<a class="btn btn-success" href="<?php echo url_for('nm/calCreate')?>" title="Click here to create new calendar"><i class="icon-plus"></i> Create calendar</a>


<div id="delete-cal-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3 id="myModalLabel">Delete Calendar</h3>
	</div>
	<div class="modal-body">
		<p>Are you sure you want to delete the calendar?</p>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">No</button>
		<a class="btn btn-primary" href="#">Yes</a>
	</div>
</div>

<?php use_javascript('/js/neverMiss/calList.js');?>