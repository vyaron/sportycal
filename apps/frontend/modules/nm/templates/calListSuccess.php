<?php use_stylesheet('/css/neverMiss/calList.css');
?>

<div class="container">
	<div class="box-content">
		<div class="box-content-margin clearfix">
			<h2>My Calendars <a class="plan" class="<?php echo ($licenece->isEnded() ? 'ended' : '');?>" href="<?php echo url_for('/nm/pricing')?>" title="Max subscribers <?php echo $licenece->getMaxSubscribers();?>"><?php echo $licenece->getName();?></a></h2>		
			
			<hr class="cr"/>
		</div>
		
		
		<div class="cal-boxes clearfix">
		<?php if ($calList['total']): ?>
		<?php foreach ($calList['data'] as $i => $cal): ?>
			<div id="cal_<?php echo $cal['id'];?>" class="cal-box <?php echo $cal['deleted_at'] ? 'cal-is-deleted' : 'cal-is-active';?>">
				<h3><span data-placement="top" data-toggle="tooltip" data-original-title="<?php echo $cal['name'];?>"><?php echo Utils::substr($cal['name'], 10);?></span></h3>
				<div class="content">
					<ul>
						<li>Last Modified <span class="value"><?php echo date('d M Y', strtotime($cal['updated_at']));?></span></li>
						<li>Upcoming Events <span class="value"><?php echo $cal['event_count'];?></span></li>
						<li>Subscribers <span class="value"><?php echo $cal['cal_request_count'];?></span></li>
					</ul>
					
					<div class="btns clearfix">
						<div class="cal-deleted">
							<button data-cal-id="<?php echo $cal['id'];?>" href="<?php echo url_for('nm/calRestore');?>" class="btn btn-success pull-right cal-restore">Restore</button>
						</div>
						<div class="cal-active">
							<a href="<?php echo url_for('nm/calEdit/?id=' . $cal['id']);?>" class="btn btn-success pull-left">Edit</a>
							<a href="<?php echo url_for('nm/widget/?calId=' . $cal['id']);?>" class="btn pull-right">Customize</a>
						</div>
					</div>
					
					<div class="cal-active">
						<a class="delete-cal delete-ico" data-cal-id="<?php echo $cal['id'];?>" href="<?php echo url_for('nm/calDelete');?>" data-name="<?php echo $cal['name'];?>">delete calendar</a>
					</div>
				</div>
			</div>
		<?php endforeach;?>
		<?php endif;?>
			<div class="cal-box">
				<?php if ($isReachedMaxCalendars):?>
				<h3><span class="add-ico">Add New</span></h3>
				<div class="content">
					<p>To create more calendars please <a href="<?php echo url_for('nm/pricing');?>">upgrade</a></p>
				</div>
				<?php else:?>
				<h3><a href="<?php echo url_for('nm/calCreate')?>" class="add-ico">Add New</a></h3>
					<?php if (!$calList['total']):?>
					<div class="content">
						<p>create your first calendar</p>
					</div>
					<?php endif;?>
				<?php endif;?>
			</div>
		</div>
		
		<div class="box-content-margin clearfix">
			<?php include_partial('pagination', array('list' => $calList, 'url' => '/nm/calList/')); ?>
		</div>
			
		
		
		<?php if (count($licenceError)):?>
			<div class="box-content-margin clearfix">
				<hr/>
				
				<div class="upgrade-warning">
					<p><?php echo $licenceError;?></p>
					<p>Upgrade your licence:&nbsp;&nbsp;&nbsp;<a class="btn btn-success btn-mini" href="<?php echo url_for('nm/pricing');?>"><i class="icon-shopping-cart"></i> Upgrade</a></p>
				</div>
			</div>
		<?php endif;?>
		
	</div>
</div>

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


<?php if (false):?>
<div class="container">
<div class="main-container">
	<div class="main-container-content">
		<h2>My Calendars <a class="plan" class="<?php echo ($licenece->isEnded() ? 'ended' : '');?>" href="<?php echo url_for('/nm/pricing')?>" title="Max subscribers <?php echo $licenece->getMaxSubscribers();?>"><?php echo $licenece->getName();?></a></h2>
		
		<hr/>
	</div>
		
		
		
		<div class="main-container-content">
			<?php include_partial('pagination', array('list' => $calList, 'url' => '/nm/calList/')); ?>
		</div>
	</div>
</div>
</div>


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

<h2>My Calendars <a id="plan-box" class="<?php echo ($licenece->isEnded() ? 'ended' : '');?>" href="<?php echo url_for('/nm/pricing')?>" title="Max subscribers <?php echo $licenece->getMaxSubscribers();?>">[*] <?php echo $licenece->getName();?></a></h2>

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



</div>
<?php endif;?>


<?php use_javascript('/js/neverMiss/calList.js');?>