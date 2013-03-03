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
        
			<h2 class="center">Users List (<?php echo count($users) ?>)</h2>
			
			<table cellspacing="15px">
			  <thead>
			    <tr>
			      <th>&nbsp;</th>
			      <th>Id</th>
			      <th>Full name</th>
			      <th>Created at</th>
			      <th>Gender</th>
			      <th>Last login</th>
			      <th>Email</th>
			      <th>Birthdate</th>
			      <th>
			      	<a class="tableSorter<?php echo ($sort == 'balance') ? ' sort_' . $currOrderBy : '';?>" href="<?php echo url_for("admin/displayUsers?sort=balance&orderBy=" . $orderBy)?>">Balance</a>
			      </th>
			    </tr>
			  </thead>
			  <tbody>
			    <?php foreach ($users as $user): ?>
			    <tr>
			    	<td>
						<div class="divUserImg">
						    
						    <?php if ($user->getFbCode()) : ?>
						        <img src="http://graph.facebook.com/<?php echo $user->getFbCode() ?>/picture"  />
						    <?php else : ?>
						        <?php echo image_tag('user/profile.jpg', 'class="imgProfile"')?>
						    <?php endif; ?>
						</div>
				  </td>    
			      <td><a href="<?php echo url_for('user/show?id='.$user->getId()) ?>"><?php echo $user->getId() ?></a></td>
			      <td><?php echo $user->getFullName() ?></td>
			      <td><?php echo date('Y-m-d', strtotime($user->getCreatedAt())) ?></td>
			      <td><?php echo $user->getGender() ?></td>
			      <td><?php echo $user->getLastLoginDate() ?></td>
			      <td><?php echo $user->getEmail() ?></td>
			      <td><?php echo $user->getBirthdate() ?></td>
			      <td><?php echo $user->getBalance() ?></td>
			    </tr>
			    <?php endforeach; ?>
			  </tbody>
			</table>
		</td>
		<td class="tdBoxRight"></td>
    </tr>
    <tr class="">
        <td class="tdCorner tdBoxBottomLeft"></td>
        <td class="tdBoxBottomMiddle"></td>
        <td class="tdCorner tdBoxBottomRight"></td>
    </tr>
</table>

