<div class="container">

<div class="row">
	<div style="width: 220px; margin: 0 auto;">
		<?php include_partial('nm/loginForm', array('form' => $form)); ?>
	</div>
</div>

</div>
<?php
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js'); 
use_javascript('/js/neverMiss/login.js');
?>


