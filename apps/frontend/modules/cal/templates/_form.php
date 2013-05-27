<?php use_stylesheet('/css/niceUI.css');?>
<style>

    #cal_name, #val_location, #cal_description{width: 440px;}
    
</style>


<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
 
<div class="boxForm rounded10 niceUI">
<?php echo form_tag_for($form, '@cal') ?>
  <table id="cal_form" style="text-align:left;">
    <tfoot>
      <tr>
        <td colspan="2">
          <center>
          	<br/>
          	<input type="submit" value="Save" class="btn btn-success"/> &nbsp;&nbsp;
          	<a href="<?php echo url_for('cal/show?id='.$form->getObject()->getId()) ?>">Cancel</a>
          </center>

        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>
</form>
</div>