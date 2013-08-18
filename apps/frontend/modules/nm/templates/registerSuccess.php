<?php use_stylesheet('/css/neverMiss/login.css');?>
<div class="container">

<?php include_partial('formError', array('form' => $form)) ?>

<div id="form-box-center">
	<?php include_partial('nm/registerForm', array('form' => $form)); ?>
</div>



</div>
<?php
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js'); 
use_javascript('/js/neverMiss/register.js');
?>


