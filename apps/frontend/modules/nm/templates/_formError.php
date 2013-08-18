<?php if ($form->hasErrors()): ?>
<div class="alert alert-error">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4>Error!</h4>

	<ul>
	<?php foreach ($form->getErrorSchema() as $name => $error): ?>
		<li><?php echo $name;?> - <?php echo $error;?></li>
	<?php endforeach;?>
	</ul>
</div>
<?php endif;?>