<?php use_stylesheet('/css/neverMiss/login.css');?>
<div class="container">

<?php include_partial('nm/formError', array('form' => $form)) ?>

<div id="form-box-center">
	<?php include_partial('nm/loginForm', array('form' => $form)); ?>
</div>



</div>
<?php
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js'); 
use_javascript('/js/neverMiss/login.js');
?>


