<h2>Calendars</h2>

<?php if (!count($cals)): ?>
<p>No calendars!</p>
<?php else:?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Events</th>
				<th>subscribers</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($cals as $cal):?>
			<tr>
				<td><?php echo date('Y-m-d H:s', strtotime($cal['updated_at']));?></td>
				<td><?php echo $cal['name'];?></td>
				<td><?php echo $cal['event_count'];?></td>
				<td><?php echo $cal['cal_request_count'];?></td>
				<td>
					<a class="btn btn-mini" href="<?php echo url_for('nm/widget/?calId=' . $cal['id']);?>">&lt;Embed/&gt;</a>
					<a class="btn btn-mini" href="<?php echo url_for('nm/calEdit/?id=' . $cal['id']);?>"><i class="icon-pencil"></i> Edit</a>
					<a class="btn btn-mini delete-cal" href="<?php echo url_for('nm/calDelete/?id=' . $cal['id']);?>" data-name="<?php echo $cal['name'];?>"><i class="icon-trash"></i> Delete</a>
				</td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
<?php endif;?>
<a class="btn btn-success" href="<?php echo url_for('nm/calCreate')?>" title="Click here to create new calendar"><i class="icon-plus"></i> Create calendar</a>


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

<?php 
use_javascript('/bundle/bootstrap/js/bootstrap.min.js');
use_javascript('/js/neverMiss/calList.js');
?>