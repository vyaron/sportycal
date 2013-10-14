<?php
	$language = Utils::iff($language, '');
?>
<form id="widget-form" class="form-horizontal" method="GET">
	<div class="control-group">
		<label class="control-label" for="btn-style">Button Style:</label>
		<div class="controls">
			<select id="btn-style" name="btn-style">
				<option value="<?php echo NeverMissWidget::DEFAULT_VALUE;?>">Default</option>
				<option value="only_icon">Only icon</option>
				<option value="list">List</option>
			</select>
		</div>
	</div>
	<div id="upcoming-wrapper" class="control-group" style="display: none;">
		<label class="control-label" for="upcoming">Upcoming Events:</label>
		<div class="controls">
			<select id="upcoming" name="upcoming">
				<?php for ($i=1; $i <= 5; $i++):?>
				<option value="<?php echo $i;?>" <?php echo ($upcoming == $i) ? 'selected="selected"' : '';?>>
					<?php echo $i;?>
				</option>
				<?php endfor;?>
			</select>
		</div>
	</div>
	<div id="btn-size-wrapper" class="control-group">
		<label class="control-label" for="btn-size">Button Size:</label>
		<div class="controls">
			<select id="btn-size" name="btn-size">
				<option value="<?php echo NeverMissWidget::DEFAULT_VALUE;?>">Default</option>
				<option value="small">Small</option>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="color">Color:</label>
		<div class="controls">
			<select id="color" name="color">
				<option value="<?php echo NeverMissWidget::DEFAULT_VALUE;?>">Default</option>
				<option value="dark">Dark</option>
				<option value="yellow">Yellow</option>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="language">Language:</label>
		<div class="controls">
			<select id="language" name="language">
				<?php foreach (NeverMissWidget::$LANGUAGES_OPTIONS as $value => $name):?>
				<option value="<?php echo $value;?>" <?php echo ($value == $language) ? ' selected="selected"' : ''?>>
					<?php echo $name;?>
				</option>
				<?php endforeach;?>
			</select>
		</div>
	</div>
</form>
