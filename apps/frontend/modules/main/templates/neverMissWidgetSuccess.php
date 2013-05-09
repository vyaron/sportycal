<?php sfContext::getInstance()->getResponse()->addStylesheet('downloadWidget.css');?>

<form>
<table id="downloadWidget" class="rbstTable" style="width: 650px; height: 400px;">
	<tr>
		<td class="rbstTL"></td>
		<td class="rbstTC">
			<h2><?php echo __('Customize your Widget');?></h2>
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
							<form method="POST">
								<tr>
									<td><label for="label"><?php echo __('Calendar Id');?>:</label></td>
									<td>
										<input type="text" name="calId" value="<?php echo $calId;?>"/>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<input type="submit" id="doItBtn" value="<?php echo __('Apply');?>"/>
									</td>
								</tr>
							</form>
							<tr>
								<td colspan="2">
									<div id="jsCodeWrapper" class="hidden pb10">
										<label for="copyJsCode"><?php echo __('Copy this code to your site (iframe)');?>: </label>
										<textarea id="copyJsCode" rows="3" cols="3" spellcheck="false">&lt;div class=&quot;nm-follow&quot; data-cal-id=&quot;<?php echo $calId;?>&quot; style=&quot;float:left&quot;&gt;&lt;/div&gt; &lt;script&gt;(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = &quot;//sportycal.local/neverMissWidget/js/all.js&quot;;fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'never-miss-jssdk'));&lt;/script&gt;</textarea>
									</div>
									<input id="iAgree" type="checkbox"/><label id="dwTermsWrapper"><?php echo __('I agree');?>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="/main/downCalTerms"><?php echo __('Terms & Conditions');?></a></label>
								</td>
							</tr>
						</table>
						
					</td>
					<td>
						<div class="nm-follow" data-cal-id="<?php echo $calId;?>" style="float:left"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//sportycal.local/neverMissWidget/js/all.js";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'never-miss-jssdk'));</script>
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

<script type="text/javascript">
window.addEvent('domready', function(){
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
