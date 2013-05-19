<?php
//class for export sheduler events in icalendar format
class ICalExporter {
	private $title; // calendar view title
	
	//set name the calendar
	function setTitle($t) {
		$this->title = $t;
	}
	
	//get calendar name
	function getTitle() {
		return $this->title;
	}
	
	//returns the string value of the day instead of its ordinal number or return number
	function getConvertDay($i, $mode=false) {
		$a = array ("SU","MO","TU","WE","TH","FR","SA");
		if($mode) {
			for($y=0;$y<sizeof($a);$y++){
				if($a[$y] == $i) {
					return $y;
				}
			}
		}
		else{
			return $a[$i];	
		}
	}
	
	//returns the appropriate line 
	function getConvertType($i, $mode=false) {
		$a = array ('day' => "DAILY",'week' => "WEEKLY",'month' => "MONTHLY",'year' => "YEARLY");
		if($mode) {
			foreach ($a as $key => $value) {
				if($a[$key] == $i) {
					return $key;
				}
			}
		}
		else {
			return $a[$i];
		}
	}
	
	//returns the strings value of the days instead of its ordinal numbers
	function getConvertDays($n, $ind=false) {
		$a = explode(",", $n);
		$str = "";
		for($i=0;$i<sizeof($a);$i++) {
			$str .= $this->getConvertDay($a[$i]);
			if($i != sizeof($a)-1) { $str .= ","; }
		}
		return $str;
	}

	//get iCal rrule for recurrence events
	function getRrule($events) {
		$mas = explode("#",$events['rec_type']);
		$a = explode("_", $mas[0]);
		
		$type = "FREQ=".$this->getConvertType($a[0]).";";
		$interval = "INTERVAL=".$a[1].";";

		if($mas[1] && $mas[1] != "no") { $count = "COUNT=".$mas[1].";"; } else { $count = ""; }
		$count2 = $a[3];
		if($a[2] != "") { $day = $this->getConvertDay($a[2]); } else { $day = ""; }
		if($a[4] != "") { $days = $this->getConvertDays($a[4]); } else { $days = ""; }
		if($day != "" and $count2 != "") {
			$byday = "BYDAY=".$count2."".$day.";";
		}
		elseif($days != "") {
			$byday = "BYDAY=".$days.";";
		}
		else {
			$byday = "";
		}
		$end_date = $this->getTime($events['end_date']);
		if(substr($end_date, 0, 4) != 9999) { $until = "UNTIL=".$end_date.";"; } else { $until = ""; };
		return $type."".$interval."".$count."".$byday."".$until;
	}
	
	//returns a string of remote events
	function getExdate($id, $h) {
		$a = array();
		$y = 0;
		for($i=0;$i<sizeof($h);$i++) {
			if($id == $h[$i]['event_pid'] and $h[$i]['rec_type'] == "none") {
				$a[$y] = date("Ymd\THis\Z", strtotime($h[$i]['start_date']));
				$y++;
			}
		}
		if(sizeof($a) != 0) {
			return implode(",", $a);
		}
		else {
			return 0;
		}
	}

	//get date in icalendater format
	function getStartTimeEvent($event) {
		$mas = explode("#",$event['rec_type']);
		$a = explode("_", $mas[0]);		
		switch($a[0]) {
			case "day":
				return $this->getTime($event['start_date']);
				break;
			
			case "week":
				$diff = explode(",",$a[4]);
				if($diff[0] == 0) { 
					$n = 7;
				}
				else {
					$n = $diff[0];
				}
				$day = date("j", strtotime($event['start_date'])) + $n - 1;
				if($day < 10) { $day = "0".$day; }
				return date("Ym",strtotime($event['start_date']))."".$day."T".date("His",strtotime($event['start_date'])) . 'Z';
				break;
				
			case "month":
			case "year":
				if($a[2] != "" and $a[3] != "") {
					$diff = $a[2] - date("N", strtotime($event['start_date']));
					if($diff > 0) { $diff -= 7; }
					$day = 7*$a[3] + $diff + 1;
					if($day < 10) { $day = "0".$day; }
					return date("Ym",strtotime($event['start_date']))."".$day."T".date("His",strtotime($event['start_date'])) . 'Z';
				}
				else {
					return $this->getTime($event['start_date']);
				}
				break;
		}
	}
	
	function getEndTimeEvent($event) {
		$start_date = strtotime($this->getStartTimeEvent($event));
		return date("Ymd\THis\Z",$start_date+$event['event_length']);
	}
	
	function getTime($date) {
		$mas = explode('-',$date);
		if($mas[0] == 9999) { 
			return "99990201T000000Z";
		}
		else {
			return date("Ymd\THis\Z",strtotime($date));
		}
	}

	//convert the information from the array in icalendar format
	function toICal($h) {
		$str = "BEGIN:VCALENDAR\n";
		$str .= "VERSION:2.0\n";
		$str .= "PRODID:-//sportYcal//NONSGML v2.2//EN\n";
		$str .= "CALSCALE:GREGORIAN\n";
		$str .= "X-WR-TIMEZONE;VALUE=TEXT:US/Pacific\n";
		$str .= "METHOD:PUBLISH\n";

		$title = $this->getTitle();
		if ($title) $str .= "X-WR-CALNAME:".$title."\n";

		for($i=0;$i<sizeof($h);$i++) {
			$pid = (isset($h[$i]['event_pid'])) ? $h[$i]['event_pid'] : null;
			$recType = (isset($h[$i]['rec_type'])) ? $h[$i]['rec_type'] : null;
			
			if($pid != 0 and $recType == "") {
				$str .= "BEGIN:VEVENT\n";
				$str .= "DTSTART:".$this->getTime($h[$i]['start_date'])."\n";
				$str .= "DTEND:".$this->getTime($h[$i]['end_date'])."\n";
				$str .= "RECURRENCE-ID:".date("Ymd\THis\Z", strtotime($h[$i]['start_date']))."\n";
				$str .= "UID:".$h[$i]['event_pid']."\n";
				$str .= "SUMMARY:".$h[$i]['text']."\n";
				$str .= "DESCRIPTION:".$h[$i]['details']."\n";
				$str .= "LOCATION:".$h[$i]['location']."\n";
				
				if (isset($h[$i]['reminder'])){
					$str .= "BEGIN:VALARM\n";
					$str .= "TRIGGER:-PT" . ($h[$i]['reminder'] * 60) . "M\n";
					$str .= "ACTION:DISPLAY\n";
					$str .= "DESCRIPTION: Are you ready for the game?\n";
					$str .= "END:VALARM\n";
				}
				
				$str .= "END:VEVENT\n";
			} elseif($recType != "" and $pid == 0) {
				$str .= "BEGIN:VEVENT\n";
				$str .= "DTSTART:".$this->getStartTimeEvent($h[$i])."\n";
				$str .= "DTEND:".$this->getEndTimeEvent($h[$i])."\n";
				$str .= "RRULE:".$this->getRrule($h[$i])."\n";
				$exdate = $this->getExdate($h[$i]['event_id'], $h);
				if($exdate != 0) { $str .= "EXDATE:".$exdate."\n"; }
				$str .= "UID:".$h[$i]['event_id']."\n";
				$str .= "SUMMARY:".$h[$i]['text']."\n";
				$str .= "DESCRIPTION:".$h[$i]['details']."\n";
				$str .= "LOCATION:".$h[$i]['location']."\n";
				
				if (isset($h[$i]['reminder'])){
					$str .= "BEGIN:VALARM\n";
					$str .= "TRIGGER:-PT" . ($h[$i]['reminder'] * 60) . "M\n";
					$str .= "ACTION:DISPLAY\n";
					$str .= "DESCRIPTION: " . $h[$i]['remider_msg'] . "\n";
					$str .= "END:VALARM\n";
				}
				
				$str .= "END:VEVENT\n";
			} elseif($recType == "" and $pid == 0) {
				$str .= "BEGIN:VEVENT\n";
				$str .= "DTSTART:".$this->getTime($h[$i]['start_date'])."\n";
				$str .= "DTEND:".$this->getTime($h[$i]['end_date'])."\n";
				$str .= "UID:".$h[$i]['event_id']."\n";
				$str .= "SUMMARY:".$h[$i]['text']."\n";
				$str .= "DESCRIPTION:".$h[$i]['details']."\n";
				$str .= "LOCATION:".$h[$i]['location']."\n";
				
				if (isset($h[$i]['reminder'])){
					$str .= "BEGIN:VALARM\n";
					$str .= "TRIGGER:-PT" . ($h[$i]['reminder'] * 60) . "M\n";
					$str .= "ACTION:DISPLAY\n";
					$str .= "DESCRIPTION: Are you ready for the game?\n";
					$str .= "END:VALARM\n";
				}
				
				$str .= "END:VEVENT\n";
			}
		}
		
		$str .= "END:VCALENDAR";
		
		return $str;
	}
}


