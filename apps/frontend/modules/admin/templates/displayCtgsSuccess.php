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
        
			<h2 class="center">Categorys List (<?php echo count($categorys) ?>)</h2>
			
			<table cellspacing="15px">
			  <thead>
			    <tr>
			      <th><a href='<?php echo url_for("admin/displayCtgs?sort=id&orderBy=" . $orderBy)?>' class="tableSorter<?php echo ($sort == 'id') ? ' sort_' . $currOrderBy : '';?>" >Id</a></th>
			      <th>Name</th>
			      <th>Image path</th>
			      <th>By user</th>
			      <th>Approved at</th>
			      <th>Parent</th>
				  <th><a href='<?php echo url_for("admin/displayCtgs?sort=rate&orderBy=" . $orderBy)?>' class="tableSorter<?php echo ($sort == 'rate') ? ' sort_' . $currOrderBy : '';?>" >Rate</a></th>
			      <th><a href='<?php echo url_for("admin/displayCtgs?sort=cals_count&orderBy=" . $orderBy)?>' class="tableSorter<?php echo ($sort == 'cals_count') ? ' sort_' . $currOrderBy : '';?>" >Cals count</a></th>
			    </tr>
			  </thead>
			  <tbody>
			    <?php foreach ($categorys as $category): ?>
			    <tr>
			      <td><a href="<?php echo url_for('category/show?id='.$category->getId()) ?>"><?php echo $category->getId() ?></a></td>
			      <td><?php echo $category->getName() ?></td>
			      <td><?php echo $category->getImagePath() ?></td>
			      <td><?php echo $category->getByUserId() ?></td>
			      <td><?php echo $category->getApprovedAt() ?></td>
			      <td><?php echo $category->getParentId() ?></td>
			      <td><?php echo $category->getRate() ?></td>
			      <td><?php echo $category->getCalsCount() ?></td>
			    </tr>
			    <?php endforeach; ?>
			  </tbody>
			</table>
			
			<a href="<?php echo url_for('category/new') ?>">New</a>
			
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
