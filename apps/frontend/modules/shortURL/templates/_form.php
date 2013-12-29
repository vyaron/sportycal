<?php use_stylesheet('/css/shortUrl.css');?>
	
<form action="<?php echo url_for('shortURL/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
	<?php echo $form['_csrf_token']->render();?>
	<?php echo $form['id']->render();?>
	
	<?php echo $form->renderGlobalErrors() ?>
    	
	<?php echo $form['hash']->render(array('placeholder' => 'text-to-show', 'class' => 'block'));?>
	<?php echo $form['hash']->renderError();?>
	
	<?php echo $form['url']->render(array('placeholder' => 'http://domain.com', 'class' => 'block'));?>
	<?php echo $form['url']->renderError();?>
	
	<?php if ($user->isMaster()):?>
		<?php echo $form['comment']->render(array('placeholder' => 'comment'));?>
		<?php echo $form['comment']->renderError();?>
		
		<?php echo $form['partner_id']->render();?>
		<?php echo $form['partner_id']->renderError();?>
		
		<?php echo $form['label']->render(array('placeholder' => 'label'));?>
		<?php echo $form['label']->renderError();?>
	<?php elseif($user->isPartner()):?>
		<input type="hidden" name="l[partner_id]" value="<?php echo $user->getPartner()->getId();?>" />
	<?php endif;?>
		
		
	<div class="actions clearfix">
		<?php if ($user->isMaster()):?>
		<a href="<?php echo url_for('shortURL/index') ?>">Back to list</a>
		
        <?php if (!$form->getObject()->isNew()): ?>
			&nbsp;<?php echo link_to('Delete', 'shortURL/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
		<?php endif; ?>
		&nbsp;
		<?php endif;?>
		
		<button type="submit" class="btn">Save</button>
	</div>
</form>
