<form id="searchForm" action="<?php echo url_for('main/search')?>">
	<input name="fromDate" type="hidden" class="sbDatePick"	id="mainSearchFromDate" value="<?php echo $fromDate ?>" />
	<input name ="toDate" type="hidden" class="sbDatePick" 	id="mainSearchToDate" value="<?php echo $toDate ?>" />
	
	<label for="txtSearch">Search:</label>
	<input type="text" name="txtSearch" id="txtSearch" value="<?php echo $txtSearch ?>" MAXLENGTH="100"/>
	
	<a id="selectDatesBtn" >Use Dates</a>
	
	<div id="selectDates" class="hidden">
		<div id="selectStartDate">
			<label>Start:</label>
			<select id="dayS">
			<?php for($i=1; $i<=31; $i++):?>
				<option value="<?php echo $i?>"><?php echo $i?></option>
			<?php endfor;?>
			</select>
			<select id="mouthS">
			<?php for($i=1; $i<=12; $i++):?>
				<option value="<?php echo $i?>"><?php echo $i?></option>
			<?php endfor;?>
			</select>
			<select id="yearS">
			<?php for($i = (date('Y')); $i <= (date('Y') + 1); $i++):?>
				<option value="<?php echo $i?>"><?php echo $i?></option>
			<?php endfor;?>
			</select>
		</div>
		
		<div id="selectEndDate">
			<label>End:</label>
			<select id="dayE">
			<?php for($i=1; $i<=31; $i++):?>
				<option value="<?php echo $i?>"><?php echo $i?></option>
			<?php endfor;?>
			</select>
			<select id="mouthE">
			<?php for($i=1; $i<=12; $i++):?>
				<option value="<?php echo $i?>"><?php echo $i?></option>
			<?php endfor;?>
			</select>
			<select id="yearE">
			<?php for($i = (date('Y')); $i <= (date('Y') + 2); $i++):?>
				<option value="<?php echo $i?>"><?php echo $i?></option>
			<?php endfor;?>
			</select>
		</div>
		
		<a id="removeDatesBtn" >Remove Dates</a>
	</div>
	
	
	
	<input type="submit" id="btnSearch" value="Search"/>
</form>



<script type="text/javascript">
//calc time onSubmit
var gGetTime = false;

function gSetDatesFromServer(){
	var mainSearchFromDate = document.getElementById('mainSearchFromDate');
	var mainSearchToDate = document.getElementById('mainSearchToDate');

	var selectDatesBtn = document.getElementById('selectDatesBtn');
	var selectDates = document.getElementById('selectDates');

	if (mainSearchFromDate.value && mainSearchToDate.value){
		selectDates.setAttribute('class', '');
		selectDatesBtn.setAttribute('class', 'hidden');

		gGetTime = true;
	}

	var now = new Date();

	var startDate = now;
	if (mainSearchFromDate.value){
		startDate = new Date(mainSearchFromDate.value * 1000);
	}
	
	var dayS = startDate.getDate();
	var mouthS = startDate.getMonth() +1;
	var yearS = startDate.getFullYear();

	var daySEl = document.getElementById('dayS');
	if (daySEl){
		daySEl.value = dayS;
	}

	var mouthSEl = document.getElementById('mouthS');
	if (mouthSEl){
		mouthSEl.value = mouthS;
	}

	var yearSEl = document.getElementById('yearS');
	if (yearSEl){
		yearSEl.value = yearS;
	}
	

	var endDate = now;
	if (mainSearchToDate.value){
		endDate = new Date(mainSearchToDate.value  * 1000);
	}
	
	

	var dayE = endDate.getDate();
	var mouthE = endDate.getMonth() +1;
	var yearE = endDate.getFullYear();

	var dayEEl = document.getElementById('dayE');
	if (dayEEl){
		dayEEl.value = dayE;
	}

	var mouthEEl = document.getElementById('mouthE');
	if (mouthEEl){
		mouthEEl.value = mouthE;
	}

	var yearEEl = document.getElementById('yearE');
	if (yearEEl){
		yearEEl.value = yearE;
	}
	
}

onload = function(){
	gSetDatesFromServer();

	var selectDatesBtn = document.getElementById('selectDatesBtn');
	if (selectDatesBtn){
		
		selectDatesBtn.onclick = function(){
			var selectDates = document.getElementById('selectDates');
			selectDates.setAttribute('class', '');
			selectDatesBtn.setAttribute('class', 'hidden');

			gGetTime = true;
			
			return false;
		};
	}

	var removeDatesBtn = document.getElementById('removeDatesBtn');
	if (removeDatesBtn){
		removeDatesBtn.onclick = function(){
			var selectDatesBtn = document.getElementById('selectDatesBtn');
			var selectDates = document.getElementById('selectDates');

			selectDates.setAttribute('class', 'hidden');
			selectDatesBtn.setAttribute('class', '');

			document.getElementById('mainSearchFromDate').value = '';
			document.getElementById('mainSearchToDate').value = '';
			
			gGetTime = false;
			
			return false;
		};
	}
	
	var btnSearch = document.getElementById('btnSearch');
	if (btnSearch){
		btnSearch.onclick = function(e){
			if (gGetTime){
				var daySEl = document.getElementById('dayS').value;
				var mouthSEl = document.getElementById('mouthS').value -1;
				var yearSEl = document.getElementById('yearS').value;

				var dayEEl = document.getElementById('dayE').value;
				var mouthEEl = document.getElementById('mouthE').value -1;
				var yearEEl = document.getElementById('yearE').value;
	
				var tzMin = -(new Date).getTimezoneOffset();
				
				if (daySEl && (mouthSEl || mouthSEl == 0) && yearSEl && dayEEl && (mouthEEl || mouthEEl == 0) && yearEEl){
					
					var start = new Date(yearSEl, mouthSEl, daySEl, tzMin/60, tzMin%60);
					var end = new Date(yearEEl, mouthEEl, dayEEl, tzMin/60, tzMin%60);

					if (start <= end){
						var mainSearchFromDate = document.getElementById('mainSearchFromDate');
						mainSearchFromDate.value = Math.floor(start/1000);
						
						var mainSearchToDate = document.getElementById('mainSearchToDate');
						mainSearchToDate.value = Math.floor(end/1000);
					} else {
						alert('error');
						return false;
					}
				}
			}
		};
	}
};

</script>

