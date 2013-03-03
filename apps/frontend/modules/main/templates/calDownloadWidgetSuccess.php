<?php sfContext::getInstance()->getResponse()->addStylesheet('downloadWidget.css');?>

<form>
<table id="downloadWidget" class="rbstTable" style="width: 650px; height: 400px;">
	<tr>
		<td class="rbstTL"></td>
		<td class="rbstTC">
			<h2><?php echo __('Customize your Cal-Download Widget');?></h2>
		</td>
		<td class="rbstTR"></td>
	</tr>
	<tr>
		<td class="rbstCL"></td>
		<td class="rbstCC">
			<table>
				<tr>
					<td style="width:310px;">
						
						<table>
							<tr>
								<td style=" padding-top: 10px;"><label for="calId"><?php echo __('Type');?>:</label></td>
								<td>
									<input id="calRadioEl" type="radio" name="type" value="cal" <?php echo ($calId) ? 'checked="checked"' : '';?>> <?php echo __('Calendar');?>&nbsp;&nbsp;
									<input id="ctgRadioEl" type="radio" name="type" value="ctg" <?php echo ($ctgId) ? 'checked="checked"' : '';?>> <?php echo __('Category');?>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<input id="typeId" type="hidden" name="typeId" value="<?php echo $typeId;?>"/>
									<input id="typeSearch" type="text"" autocomplete="off" class="lightBorder" placeholder="<?php echo __('Start typing');?>..."/>
								</td>
							</tr>
							<?php if (false) :?>
							<tr>
								<td><label for="label"><?php echo __('Label');?>:</label></td>
								<td><input id="label" type="text" name="label" class="lightBorder" value="<?php echo $label ?>"/></td>
							</tr>
							<?php endif ?>
							<tr>
								<td><label for="color"><?php echo __('Style');?>:</label></td>
								<td>
									<select id="color" name="color">
										<?php foreach ($colors as $currColor) : ?>
										<option
										<?php echo ($currColor == $color )? 'selected="selected"' : '' ?>>
											<?php echo $currColor ?>
										</option>
										<?php endforeach ?>
									</select>
								</td>
							</tr>
							<tr>
								<td><label for="lang"><?php echo __('Language');?>:</label></td>
								<td>
									<select id="lang" name="language">
										<?php foreach ($languages as $lang) : ?>
										<option
										<?php echo ($lang == $language )? 'selected="selected"' : '' ?>>
											<?php echo $lang ?>
										</option>
										<?php endforeach ?>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<a id="doItBtn" href="#"><?php echo __('Apply');?></a>
									<div class="cb"></div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div id="jsCodeWrapper" class="hidden pb10">
										<label for="copyJsCode"><?php echo __('Copy this code to your site (iframe)');?>: </label>
										<textarea id="copyJsCode" rows="3" cols="3" spellcheck="false"><?php echo $copyIframeCode;?></textarea>
									</div>
									<input id="iAgree" type="checkbox"/><label id="dwTermsWrapper"><?php echo __('I agree');?>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="/main/downCalTerms"><?php echo __('Terms & Conditions');?></a></label>
								</td>
							</tr>
						</table>
						
					</td>
					<td>
						<?php echo $showWidget ? $sf_data->getRaw('iframeCode') : '';?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<p id="partnerMsg">
						<?php echo __('To customize which text is presented in the event description');?>, 
						<?php echo __('email to');?>: <a href="mailto://info@sportYcal.com">info@sportYcal.com</a>
						<?php echo __('to get your partner-id');?>.<br/>
						<?php echo __('If you already have your partner-id, enter here');?>: 
						<input class="lightBorder" type="text" name="ref" value="<?php echo $inRef ?>" style="width: 150px" /> 
						<?php echo __('and click Apply');?>
						</p>
					</td>
				</tr>
			</table>
		</td>
		<td class="rbstCR"></td>
	</tr>
	<tr>
		<td class="rbstBL"></td>
		<td class="rbstBC"></td>
		<td class="rbstBR"></td>
	</tr>
</table>
</form>

<div id="ajaxDownloadWidgetSearch" class="ajaxSearch hidden"></div>

<script type="text/javascript">
window.addEvent('domready', function(){
	var typeSearch = $('typeSearch');
	if (typeSearch){
		var downloadWidgetSearch = new Search(typeSearch, {
			ajaxSearchEl : $('ajaxDownloadWidgetSearch'),
			submitEls : [$('doItBtn')],
			hiddenEl : $('typeId'),
			disableEnterSubmit : true,
			searchType : '<?php echo ($type) ? $type : 'cal';?>'
		});

		var calRadioEl = $('calRadioEl');
		if (calRadioEl){
			calRadioEl.addEvent('click', function(){
				downloadWidgetSearch.setSearchType('cal');
				downloadWidgetSearch.hideAjaxSearch();
				downloadWidgetSearch.onKeyUp();
			});
		}

		var ctgRadioEl = $('ctgRadioEl');
		if (ctgRadioEl){
			ctgRadioEl.addEvent('click', function(){
				downloadWidgetSearch.setSearchType('ctg');
				downloadWidgetSearch.hideAjaxSearch();
				downloadWidgetSearch.onKeyUp();
			});
		}
	}

	var copyJsCode = $('copyJsCode');
	if (copyJsCode){
		copyJsCode.addEvent('focus', function(e){
			e.stop();
			copyJsCode.select();
		});
	}

	var iAgree = $('iAgree');
	if (iAgree){
		iAgree.addEvent('click', function(e){
			//e.stop();

			var jsCodeWrapper = $('jsCodeWrapper');
			
			if (iAgree.get('checked')){
				jsCodeWrapper.removeClass('hidden');
			} else {
				jsCodeWrapper.addClass('hidden');
			}

		});
	}
});

</script>
