<style>

    #category_name {
        width:500px;
    }
    
    #category_parent_id {
        width:60px;
    }
</style>


<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="boxForm rounded10">
<form action="<?php echo url_for('category/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table style="text-align:left">
    <tfoot>
      <tr>
        <td colspan="2">
          <center>
          	<br/>
          	<input type="submit" value="Save" /> &nbsp;&nbsp;
          	<a href="<?php echo url_for('category/show?id='.$parentId) ?>">Cancel</a>
          </center>
        </td>
      </tr>
    </tfoot>
    <tbody>
	  <input type="hidden" name="category[parent_id]" value="<?php echo $parentId ?>" />
      <?php echo $form ?>
    </tbody>
  </table>
</form>
</div>
