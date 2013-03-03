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
        
			<h2 class="center">Short URLs</h2>
			
			<table cellspacing="15px">
			  <thead>
			    <tr>
				  <th>ID</th>
			      <th>Hash</th>
			      <th>Comment</th>
			      <th>Last used</th>
			      <th>Count used</th>
			      <th>URL</th>
			      <th>Created</th>
			      <th><a class="tableSorter<?php echo ($sort == 'partner_id') ? ' sort_' . $currOrderBy : '';?>" href="<?php echo url_for("shortURL/index?sort=partner_id&orderBy=" . $orderBy)?>">Partner ID</a></th>
			      <th>Short URL</th>

			    </tr>
			  </thead>
			  <tbody>
			    <?php foreach ($shortURLs as $shortURL): ?>
			    <tr>
			      <td><a href="<?php echo url_for('shortURL/show?id='.$shortURL->getId()) ?>"><?php echo $shortURL->getId() ?></a></td>
			      <td><?php echo $shortURL->getHash() ?></td>
			      <td><?php echo $shortURL->getComment() ?></td>
			      <td><?php echo $shortURL->getUsedAt() ?></td>
			      <td><?php echo $shortURL->getCountUsed() ?></td>
			      <td><?php echo $shortURL->getUrl() ?></td>
			      <td><?php echo $shortURL->getCreatedAt() ?></td>
			      <td><?php echo $shortURL->getPartnerId() ?></td>
			      <td><a href="<?php echo $shortURL->getShortcut()?>" target="_blank"><?php echo $shortURL->getShortcut() ?></a></td>
			      
			      
			    </tr>
			    <?php endforeach; ?>
			  </tbody>
			</table>
			
			<a href="<?php echo url_for('shortURL/new') ?>"><?php echo __('New');?></a>
		</td>
		<td class="tdBoxRight"></td>
    </tr>
    <tr class="">
        <td class="tdCorner tdBoxBottomLeft"></td>
        <td class="tdBoxBottomMiddle"></td>
        <td class="tdCorner tdBoxBottomRight"></td>
    </tr>
</table>

