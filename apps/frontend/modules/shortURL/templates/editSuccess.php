<div class="container">
	<div id="form-box-center">
		<div class="form-box">
			<h2>
				<span class="content"><strong>Edit Short url</strong></span>
			</h2>
			
			<span class="content">
				<input type="text" id="shortUrlVal" class="block" value="<?php echo $form->getObject()->getShortcut()?>" onclick="window.prompt ('Copy to clipboard: Ctrl+C, Enter', this.value);"/>
				
				<?php include_partial('form', array('form' => $form, 'user' => $user)) ?>
			</span>
		</div>
		
	</div>
</div>

