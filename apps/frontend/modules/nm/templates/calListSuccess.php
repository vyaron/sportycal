<?php use_stylesheet('/css/neverMiss/calList.css');?>

<div class="container">

<?php if (count($licenceErrors)):?>
	<div class="alert alert-block alert-warning">
		<h4>You have reached your liscence limits</h4>
		<ul>
		<?php foreach($licenceErrors as $licenceError):?>
			<li><?php echo $licenceError;?></li>
		<?php endforeach;?>
		</ul>
		<p>Upgrade your licence:&nbsp;&nbsp;&nbsp;<a class="btn btn-mini" href="<?php echo url_for('nm/pricing');?>"><i class="icon-shopping-cart"></i> Upgrade</a></p>
	</div>
<?php endif;?>

<h2>Calendars <a id="plan-box" class="<?php echo ($licenece->isEnded() ? 'ended' : '');?>" href="<?php echo url_for('/nm/pricing')?>" title="Max subscribers <?php echo $licenece->getMaxSubscribers();?>">[*] <?php echo $licenece->getName();?></a></h2>

<?php if (!$calList['total']): ?>
<p>No calendars!</p>
<?php else:?>
<?php include_partial('pagination', array('list' => $calList, 'url' => '/nm/calList/')); ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>Events</th>
			<th>subscribers</th>
			<th class="hidden-phone">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($calList['data'] as $i => $cal): ?>
		<tr id="cal_<?php echo $cal['id'];?>" class="<?php echo $cal['deleted_at'] ? 'cal-is-deleted' : 'cal-is-active';?>"/>
			<td><?php echo ($i + 1);?></td>
			<td><?php echo $cal['name'];?></td>
			<td><?php echo $cal['event_count'];?></td>
			<td>
				<span><?php echo $cal['cal_request_count'];?></span>
			</td>
			<td class="hidden-phone">
				<div class="cal-active">
					<a class="btn btn-mini" href="<?php echo url_for('nm/widget/?calId=' . $cal['id']);?>">&lt;Embed/&gt;</a>
					<a class="btn btn-mini" href="<?php echo url_for('nm/calEdit/?id=' . $cal['id']);?>"><i class="icon-pencil"></i> Edit</a>
					<a class="btn btn-mini delete-cal" data-cal-id="<?php echo $cal['id'];?>" href="<?php echo url_for('nm/calDelete');?>" data-name="<?php echo $cal['name'];?>"><i class="icon-trash"></i> Delete</a>
				</div>
				<div class="cal-deleted">
					<a class="btn btn-mini cal-restore" data-cal-id="<?php echo $cal['id'];?>" href="<?php echo url_for('nm/calRestore');?>"><i class="icon-refresh"></i> Restore</a>
				</div>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>

<?php include_partial('pagination', array('list' => $calList, 'url' => '/nm/calList/')); ?>
<?php endif;?>

<?php if ($isReachedMaxCalendars):?>
<a class="btn btn-success disabled" href="#" title="<?php echo __('Reached calendars limit');?>">New calendar</a>
<?php else:?>
<a class="btn btn-success hidden-phone" href="<?php echo url_for('nm/calCreate')?>" title="Click here to create new calendar"><i class="icon-plus icon-yellow"></i> New calendar</a>
<?php endif;?>

<div id="delete-cal-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3 id="myModalLabel">Delete Calendar</h3>
	</div>
	<div class="modal-body">
		<p>Are you sure you want to delete "<span id="delete-cal-name"></span>" ?</p>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">No</button>
		<a id="delete-btn" class="btn btn-primary" href="#">Yes</a>
	</div>
</div>

</div>
<?php use_javascript('/js/neverMiss/calList.js');?>