<?php use_stylesheets_for_form($form) ?>


<?php use_stylesheet('/css/niceUI.css');?>
<?php use_stylesheet('/css/shortUrl.css');?>

<?php use_javascripts_for_form($form) ?>

<div class="shortUrl">
<form class="niceUI" action="<?php echo url_for('shortURL/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

<?php echo $form['_csrf_token']->render();?>
<?php echo $form['id']->render();?>

<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
      	<td>&nbsp;</td>
        <td>
          &nbsp;<a href="<?php echo url_for('shortURL/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'shortURL/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <button type="submit" class="btn">Save</button>
        </td>
      </tr>
    </tfoot>
    <tbody>
    <?php if (true):?>
    	<?php echo $form->renderGlobalErrors() ?>
    	
		<?php echo $form['hash']->renderRow(array('placeholder' => 'my-short-text'));?>
		<?php echo $form['url']->renderRow();?>
		
		<?php if ($user->isMaster()):?>
			<?php echo $form['comment']->renderRow();?>
			<?php echo $form['partner_id']->renderRow();?>
			<?php echo $form['label']->renderRow();?>
		<?php elseif($user->isPartner()):?>
			<input type="hidden" name="l[partner_id]" value="<?php echo $user->getPartner()->getId();?>" />
		<?php endif;?>
	<?php endif;?>
      	
      <?php //echo $form ?>
    </tbody>
  </table>
</form>

</div>
