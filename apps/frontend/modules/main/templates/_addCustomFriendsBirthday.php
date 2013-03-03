<?php ?>
<div id="addCustromFriendBirthday">
	<div id="acfbL">
		<label for="cfName">Friend Name:</label>
		<input type="text" id="cfName" name="cfName" />
		
		<div id="acfbDate">
			<label>Date (d/m/Y):</label>
			<select id="cfDay" name="cfDay">
				<?php for ($i=1; $i<=31; $i++):?>
				<option value="<?php echo (($i < 10) ? '0' . $i : $i)?>"><?php echo (($i < 10) ? '0' . $i : $i)?></option>
				<?php endfor;?>
			</select>
			<select id="cfMonth" name="cfMonth">
				<?php for ($i=1; $i<=12; $i++):?>
				<option value="<?php echo (($i < 10) ? '0' . $i : $i)?>"><?php echo (($i < 10) ? '0' . $i : $i)?></option>
				<?php endfor;?>
			</select>
			<select id="cfYear" name="cfYear">
				<option value="0"></option>
				<?php for ($i=1900; $i<=date('Y'); $i++):?>
				<option value="<?php echo $i?>"><?php echo $i?></option>
				<?php endfor;?>
				</select>
		</div>
			
		<div id="acfbAddBtn" class="addBtn">
			<div class="addBtnL"></div>
			<div class="addBtnC">Add</div>
			<div class="addBtnR"></div>
			<div class="cb"></div>
		</div>
	</div>
	<div id="acfbR">
		<label>Friends Added:</label>
		<div id="cfFriendsWrapper"></div>
		<div id="dummy_customFriendBirthday" class="hidden">
			<div class="cfFriendClose" title="Remove friend"></div>
			<div class="cfFriendInfo">
				<h5 class="cfFriendName"></h5>
				<h6 class="cfFriendDate"></h6>
			</div>
			<div class="cb"></div>
		</div>
	</div>
	<div class="cb"></div>
</div>