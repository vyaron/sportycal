<style>

    #cal_name {
        width:500px;
    }
    #val_location {
        width:500px;
    }
    #cal_description {
        width:500px;
    }
    
</style>


<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
 
<div class="boxForm rounded10">
<?php echo form_tag_for($form, '@cal') ?>
  <table id="cal_form" style="text-align:left;">
    <tfoot>
      <tr>
        <td colspan="2">
          <center>
          	<br/>
          	<input type="submit" value="Save" /> &nbsp;&nbsp;
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