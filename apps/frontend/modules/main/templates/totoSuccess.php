<?php sfContext::getInstance()->getResponse()->addStylesheet('toto');?>
<div id="totoWrapper">
	<div id="totoContent">
		<div id="totoStep_1">
			<div class="totoCalTop"></div>
			<div class="totoCalHeader">
				<div class="pt20 pb8">
					<div class="winnerLogo2"></div>
					<p class="fs18 white">כל אירועי הספורט החמים ביומן שלכם!</p>
				</div>
			</div>
			<div class="totoCalHeaderBottom"></div>
			<div class="totoCalContent">
				<div class="totoCalContentPadding">
					<p class="fs24 b tar">מהיום מורידים את אירועי הספורט החמים של Winner ישירות ליומן האישי:</p>
					
					<ul class="ula pt15">
						<li>בוחרים את הליגות והקבוצות שמעניינות אותך.</li>
						<li>בוחרים לאיזה יומן להוריד את המשחקים: <br/>Outlook,לוח שנה גוגל ולוחות נוספים.</li>
						<li>לאחר ההורדה לא מפספסים שום משחק!</li>
					</ul>
					
					<ul class="ulb">
						<li>היומן יהיה יומן נפרד מהיומן האישי וניתן <a href="javascript:showUnsubscribe()" title="הסרת רישום" onClick="showUnsubscribe()">להסרה</a> בכל עת.</li>
						<li>ביומן יוצגו משחקים שמופיעים בתוכניית Winner הפעילה, כולל יחסי ההימור.</li>
						<li>המידע על המשחקים יתעדכן באופן אוטומטי גם לאחר ההורדה הראשונית.</li>
						<li>יחסי ההימור עשויים להשתנות.</li>
						<li>השירות ניתן ללא עלות.</li>
						<li>השימוש באפליקציה בהתאם <a href="http://www.winner.co.il/conditions.asp" target="_blank">לתקנון אתר הטוטו</a>.</li>
						<li>שירות לקוחות 6040 *</li>
					</ul>
					
					<div id="contBtn1" class="conBtn">
						<div class="btnL"></div>
						<div class="btnC"><span>המשך</span></div>
						<div class="btnR"></div>
						<div class="cb"></div>
					</div>
					<div class="cb"></div>
				</div>
			</div>
			<div class="totoCalBottom"></div>
		</div>
		<div id="totoStep_2" class="hide">
			<div class="winnerLogo"></div>
			<h2 class="pt20">כל אירועי הספורט החמים ביומן שלכם!</h2>
		
			<div class="totoCalTop"></div>
			<div class="totoCalHeader">
				<div id="totoStep_2_1" class="pt25 pb8">
					<select id="sports" data-default-param="בחר ענף" disabled="disabled"></select>
					<select id="factory" data-default-param="בחר מפעל" disabled="disabled"></select>
					<select id="teams" data-default-param="בחר קבוצה" disabled="disabled"></select>
				</div>
				<!-- 
				<div id="totoStep_2_2" class="pt10 hide">
					<div id="contBtn" class="conBtn">
						<div class="btnL"></div>
						<div class="btnC">המשך &gt;&gt;</div>
						<div class="btnR"></div>
						<div class="cb"></div>
					</div>
				</div>
				 -->
			</div>
			<div class="totoCalHeaderBottom"></div>
			<div class="totoCalContent">
				<div class="totoCalContentPadding">
					<div id="totoStep_2_3" class="hide">
						<h3>בחר לוח</h3>
						<div id="calsList"></div>
					</div>
					<div id="totoStep_2_4" class="pt15 hide">
						<div class="pb10 fs15">
							<?php if (!Utils::clientIsMobile() || true):?>
							<h3 class="fr">בחר לאן להוריד</h3>
							<?php endif;?>
							
							<div id="reminderWrapper" class="fl">
								<div id="helpIcon"></div>
								<input id="addReminder" name="addReminder" type="checkbox" checked="checked"/>
								<label id="addReminderLabel" for="addReminder">תזכורת</label>
								<select id="reminder" name="reminder">
									<?php foreach (CalTable::getReminderValues() as $val):?>
										<option value="<?php echo $val?>"><?php echo $val . ' ' . (($val > 1) ? __('Hours') : __('Hour'))?></option>
									<?php endforeach;?>
								</select>
							</div>
							
							<div class="cb"></div>
						</div>
						
						
						<div class="pt5">
							<?php if (Utils::clientIsMobile() && false):?>
								<?php if (Utils::clientIsAndroid()):?>
									<p class="red pb8">* אם גוגל שואל אותך האם אתה רוצה להשתמש בגרסאת סלולר - לחץ על CANCEL</p>
								<?php endif;?>
								
								<a id="calDownOnMobile" href="#" target="_blank" data-cal-type="mobile">
									<div class="btnL"></div>
									<div class="btnC"><span>הורד את לוח השנה</span></div>
									<div class="btnR"></div>
									<div class="cb"></div>
								</a>
							<?php else:?>
								<a id="calDownAny" href="#" target="_blank" class="calDown" data-cal-type="any" data-open-popup="any"><span>לוחות נוספים</span></a>
								<?php if (false):?>
				 				<a id="calDownMobile" href="#" target="_blank" class="calDown" data-cal-type="mobile" <?php echo Utils::clientIsIpad() ? '' :  'data-open-popup="mobile"';?>><span>סלולרי</span></a>
								<?php endif;?>
								<a id="calDownOutlook" href="#" target="_blank" class="calDown" data-cal-type="outlook"><span>אאוטלוק</span></a>
								<a id="calDownGoogle" href="#" target="_blank" class="calDown" data-cal-type="google"><span>לוח שנה גוגל</span></a>
								<div class="cb"></div>
							<?php endif;?>
						</div>
						
						<div class="totoCalSep"></div>
						
						<p class="gray pt5">
							עודכן:&nbsp; 
							<span id="eventsLastUpdate"></span>
						</p>
					</div>
				</div>
			</div>
			<div class="totoCalBottom"></div>
		</div>
		<div id="totoStep_3" class="hide">
			<div class="winnerLogo"></div>
			<h2 class="pt20">כל אירועי הספורט החמים ביומן שלכם!</h2>
			
			<p class="fs24 red b">מה עם לשתף?</p>
			<p class="fs14 pt5">הספורט נהדר, מקווים שתהנה מהמשחקים.</p>
			
			<div id="shareBtn" class="conBtn">
				<div class="btnL"></div>
				<div class="btnC">שתף עם החברים</div>
				<div class="btnR"></div>
				<div class="cb"></div>
			</div>
			
			<div class="pt10">
				<a id="backBtn" class="fs14 red b">בחר לוח שנה נוסף</a>
			</div>
		</div>
	</div>
</div>

<div id="dummy_nextEvent" class="hide">
	<div class="eventTimer">
		<div class="digit dh1">0</div>
		<div class="digit dh2">0</div>
		<div class="timerSep">:</div>
		<div class="digit di1">0</div>
		<div class="digit di2">0</div>
		<div class="cb"></div>
	</div>
	<div class="eventTimerDesc">האירוע הבא מתחיל בעוד</div>
	<div class="cb"></div>
</div>

<div id="dummy_cal" class="hide">
	<div class="calRowL"></div>
	<div class="calRowC">
		<div class="calIcon"></div>
		<div class="calName"></div>
		<div class="cb"></div>
	</div>
	<div class="calRowR"></div>
	<div class="cb"></div>
</div>

<div id="calDownPopup" class="hidden">
	<div id="calDownPopupBG"></div>
	<table id="calDownPopupTable">
		<tr>
			<td class="calDownPopupTL"></td>
			<td class="calDownPopupTC"></td>
			<td class="calDownPopupTR"></td>
		</tr>
		<tr>
			<td class="calDownPopupCL"></td>
			<td class="calDownPopupCC">
				<div id="calDownPopupContent">
					<!-- Help -->
					<div id="divHelp" class="calDownPopupContent">
						<div id="innerHelp" class="helpInner">
						<p class="strong"><?php echo __('Outlook');?></p>
						<p><?php echo __('Just Click to add to your Outlook Calendar 2007 and up');?></p>
						<br />
						<p class="strong"><?php echo __('Google Calendar');?></p>
						<p><?php echo __('Just Click to add to your Google Calendar');?></p>
						<br />
						<p class="tGray">
							(<?php echo __('Need help adding schedules to');?><b><?php echo __('Yahoo');?></b>? <a
								class="tGray"
								href="<?php echo url_for('help/HowToAddSportycalToYahoo.pdf') ?>"
								target="_blank"> <?php echo __('Click Here');?>) </a>
						</p>
							<br />
						</div>
					</div>

					<!-- UnSubscribe -->
					<div id="divUnsubscribe" class="calDownPopupContent">
						<div id="innerHelp" class="helpInner">
							<p> <span class="strong">אאוטלוק</span> - כנס ל"לוח שנה" ועמוד על שם הלוח, לחצן ימני בעכבר, מחק לוח שנה</p>
							<p> <span class="strong">גוגל</span> - לחץ על "לוחות שנה אחרים", לחץ על "הגדרות", מחק לוח שנה  </p>
						</div>
					</div>
					
					
					<!-- Link -->
					<div id="divCalUrl" class="calDownPopupContent">
						<div id="innerHelp" class="helpInner">
							<p class="strong"><?php echo __('Subscribe with any calendar that supports Ical');?></p>
							<br />
							<p><?php echo __('Yahoo, Lotus notes, and many others.');?></p>
							<br />
							<p><?php echo __('To add this calendar, copy & use the following URL:');?></p>
							<br />
					
							<p>
								&nbsp;&nbsp;&nbsp; <i><span id="calLink_any" class="url"></span></i>
							</p>
							<br />
							<p class="tGray"> (<?php echo __('Need help adding schedules to');?><b><?php echo __('Yahoo');?></b>? <a class="tGray" href="<?php echo url_for('help/HowToAddSportycalToYahoo.pdf') ?>" target="_blank"> <?php  echo __('Click Here');?>) </a></p>
							<br />
					
						</div>
					</div>

					<!-- Mobile -->
					<div id="divMobile" class="calDownPopupContent">
						<?php if (isset($divMobileHeader)) : ?>
						<p class="b tac" id="divMobileHeader"><?php echo $divMobileHeader ?></p>
						<?php endif ?>
						<p><span class="b"><?php echo __('iPhone/iPad calendar');?></span> - <?php echo __('use your iPhone/iPad to browse and download the calendar');?></p>
						<p><span class="b"><?php echo __('Android');?></span> - <?php  echo __('click the google calendar button to add to your google account and synch the calendar from your device, need instructions?');?> <a target="_blank" href="/help/HowToAddSportycalToAndroid.pdf"><?php echo __('see here');?></a>.</p>
					</div>
				
				</div>
				<a id="calDownPopupClose"><?php echo __('[x] Close');?></a>
			</td>
			<td class="calDownPopupCR"></td>
		</tr>
		<tr>
			<td class="calDownPopupBL"></td>
			<td class="calDownPopupBC"></td>
			<td class="calDownPopupBR"></td>
		</tr>
	</table>
</div>


<?php sfContext::getInstance()->getResponse()->addJavascript('mootools-more-1.4.0.1.js');?>
<?php sfContext::getInstance()->getResponse()->addJavascript('caldown.js'); ?>
<?php sfContext::getInstance()->getResponse()->addJavascript('Toto.class.js'); ?>
<script type="text/javascript">
var gToto = null;
window.addEvent('domready', function(){
	gToto = new Toto({
		ref : '<?php echo $partnerHash;?>',
		rootCtgId : '<?php echo $rootCtgId;?>'
	});
});

</script>