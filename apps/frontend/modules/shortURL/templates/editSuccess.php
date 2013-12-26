<h1>Edit Short url</h1>

<input type="text" class="shortUrlVal" value="<?php echo $form->getObject()->getShortcut()?>" onclick="window.prompt ('Copy to clipboard: Ctrl+C, Enter', this.value);"/>

<?php include_partial('form', array('form' => $form, 'user' => $user)) ?>
