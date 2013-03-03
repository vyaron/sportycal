<?php
	$currOrderBy = $orderBy;	

	if ($orderBy == 'desc') $orderBy = 'asc';
	else $orderBy = 'desc';
?>
<div class="tal">
	<h3><a href='<?php echo url_for("admin/index")?>' class="" > &lt; Back to Master</a></h3>
	<br/>
</div>


<table border="0px" width="" cellspacing="0px" class="">
    <tr class="">
        <td class="tdCorner tdBoxTopLeft"></td>
        <td class="tdBoxTopMiddle"></td>
        <td class="tdCorner tdBoxTopRight"></td>
    </tr>
     <tr class="trBox">
        <td class="tdBoxLeft"></td>
        <td class="tdBox">
        
			<h2 class="center">Cals List (<?php echo count($cals) ?>)</h2>
			
			<table cellspacing="15px">
			  <thead>
			    <tr>
			      <th><a class="tableSorter<?php echo ($sort == 'id') ? ' sort_' . $currOrderBy : '';?>" href="<?php echo url_for("admin/displayCalendars?sort=id&orderBy=" . $orderBy)?>">Id</a></th>
			      <th><a class="tableSorter<?php echo ($sort == 'by_user_id') ? ' sort_' . $currOrderBy : '';?>" href="<?php echo url_for("admin/displayCalendars?sort=by_user_id&orderBy=" . $orderBy)?>">By user</a></th>
			      <th><a class="tableSorter<?php echo ($sort == 'category_id') ? ' sort_' . $currOrderBy : '';?>" href="<?php echo url_for("admin/displayCalendars?sort=category_id&orderBy=" . $orderBy)?>">Category</a></th>
			      <th><a class="tableSorter<?php echo ($sort == 'name') ? ' sort_' . $currOrderBy : '';?>" href="<?php echo url_for("admin/displayCalendars?sort=name&orderBy=" . $orderBy)?>">Name</a></th>
			      <th>Primary slogan</th>
			      <th>Description</th>
			      <th><a class="tableSorter<?php echo ($sort == 'location') ? ' sort_' . $currOrderBy : '';?>" href="<?php echo url_for("admin/displayCalendars?sort=location&orderBy=" . $orderBy)?>">Location</a></th>
			      <th>Image path</th>
			      <th><a class="tableSorter<?php echo ($sort == 'access_key') ? ' sort_' . $currOrderBy : '';?>" href="<?php echo url_for("admin/displayCalendars?sort=access_key&orderBy=" . $orderBy)?>">Access key</a></th>
			      <th><a class="tableSorter<?php echo ($sort == 'created_at') ? ' sort_' . $currOrderBy : '';?>" href="<?php echo url_for("admin/displayCalendars?sort=created_at&orderBy=" . $orderBy)?>">Created at</a></th>
			      <th><a class="tableSorter<?php echo ($sort == 'updated_at') ? ' sort_' . $currOrderBy : '';?>" href="<?php echo url_for("admin/displayCalendars?sort=updated_at&orderBy=" . $orderBy)?>">Updated At</a></th>
			      <th><a class="tableSorter<?php echo ($sort == 'num_user_cal') ? ' sort_' . $currOrderBy : '';?>" href="<?php echo url_for("admin/displayCalendars?sort=num_user_cal&orderBy=" . $orderBy)?>">Count</a></th>
			    </tr>
			  </thead>
			  <tbody>
			    <?php foreach ($cals as $cal): ?>
			    <tr>
			      <td><a href="<?php echo url_for('cal/show?id='.$cal->getId()) ?>"><?php echo $cal->getId() ?></a></td>
			      <td><?php echo $cal->getByUserId() ?></td>
			      <td><?php echo $cal->getCategoryId() ?></td>
			      <td><?php echo $cal->getName() ?></td>
			      <td><?php echo $cal->getPrimarySlogan() ?></td>
			      <td><?php echo $cal->getDescription() ?></td>
			      <td><?php echo $cal->getLocation() ?></td>
			      <td><?php echo $cal->getImagePath() ?></td>
			      <td><?php echo $cal->getAccessKey() ?></td>
			      <td><?php echo $cal->getCreatedAt() ?></td>
			      <td><?php echo $cal->getUpdatedAt() ?></td>
			      <td><?php echo $cal->num_user_cal ?></td>
			    </tr>
			    <?php endforeach; ?>
			  </tbody>
			</table>
			
			<a href="<?php echo url_for('cal/new') ?>"><?php echo __('New');?></a>
		</td>
		<td class="tdBoxRight"></td>
    </tr>
    <tr class="">
        <td class="tdCorner tdBoxBottomLeft"></td>
        <td class="tdBoxBottomMiddle"></td>
        <td class="tdCorner tdBoxBottomRight"></td>
    </tr>
</table>
