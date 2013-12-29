<?php use_stylesheet('/css/shortUrl.css');?>
	
<form action="<?php echo url_for('shortURL/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
	<?php echo $form['_csrf_token']->render();?>
	<?php echo $form['id']->render();?>
	
	<?php echo $form->renderGlobalErrors() ?>
    	
    <label for="url">Target URL: </label>	
   	<?php echo $form['url']->render(array('id' => 'url', 'placeholder' => 'http://domain.com', 'class' => 'block'));?>
	<?php echo $form['url']->renderError();?> 	
    	
    <label for="hash">Text in URL: </label>
	<?php echo $form['hash']->render(array('id' => 'hash', 'placeholder' => 'text-to-show', 'class' => 'block'));?>
	<?php echo $form['hash']->renderError();?>
	
	
	
	<?php if ($user->isMaster()):?>
		<label for="comment">Comment: </label>
		<?php echo $form['comment']->render(array('id' => 'comment', 'placeholder' => 'desc...'));?>
		<?php echo $form['comment']->renderError();?>
		
		<label for="partner_id">Partner: </label>
		<?php echo $form['partner_id']->render(array('id' => 'partner_id'));?>
		<?php echo $form['partner_id']->renderError();?>
		
		<label for="label">Label: </label>
		<?php echo $form['label']->render(array('id' => 'label', 'placeholder' => 'label'));?>
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
