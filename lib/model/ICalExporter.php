<?php
//TODO: Ido add private,public,static.. ugly code

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
			return "99990101T000000Z";
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
		$str .= "X-WR-TIMEZONE;VALUE=TEXT:GMT\n";
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
					$str .= "DESCRIPTION: " . $h[$i]['reminder_msg'] . "?\n";
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
					$str .= "DESCRIPTION: " . $h[$i]['reminder_msg'] . "\n";
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
					$str .= "DESCRIPTION: " . $h[$i]['reminder_msg'] . "\n";
					$str .= "END:VALARM\n";
				}
				
				$str .= "END:VEVENT\n";
			}
		}
		
		$str .= "END:VCALENDAR";
		
		return $str;
	}
	
	
	//give date in ical format and return date in MySQL format
	function getMySQLDate($str) {
		preg_match('/[0-9]{8}[T][0-9]{6}/',trim($str),$date);
		if(isset($date[0])) {
			if($date[0] != "") {
				$y = substr($date[0], 0, 4);
				$mn = substr($date[0], 4, 2);
				$d = substr($date[0], 6, 2);
				$h = substr($date[0], 9, 2);
				$m = substr($date[0], 11, 2);
				$s = substr($date[0], 13, 2);
				return $y."-".$mn."-".$d." ".$h.":".$m.":".$s;
			}
		}
		elseif(strlen(trim($str)) == 8) {
			$y = substr($str, 0, 4);
			$mn = substr($str, 4, 2);
			$d = substr($str, 6, 2);
			return $y."-".$mn."-".$d." 00:00:00";
		}
	}
	
	//get parse a string into an array
	function getParseString($str) {
		$arr_n = array();
		$arr = explode("BEGIN:VEVENT",$str);
		for($x=1;$x<sizeof($arr);$x++) {
			$arr2 = explode("\n",$arr[$x]);
			for($y=1;$y<sizeof($arr2);$y++) {
				$mas = explode(":",$arr2[$y], 2);
				$mas_ = explode(";",$mas[0]);
				if(isset($mas_[0])){
					$mas[0] = $mas_[0];
				}
				
				$key = trim($mas[0]);
				if ($key == 'BEGIN') break;
				
				switch($key) {
					case "LOCATION":
						$arr_n[$x]['location'] = $mas[1];
						break;
					case "DESCRIPTION":
						$arr_n[$x]['description'] = str_replace('\n', "\n", $mas[1]);
						break;
					case "DTSTART":
						$arr_n[$x]['start_date'] = $this->getMySQLDate($mas[1]);
						break;
	
					case "DTEND":
						$arr_n[$x]['end_date'] = $this->getMySQLDate($mas[1]);
						break;
	
					case "RRULE":
						$rrule = explode(";", $mas[1]);
						for($z=0;$z<sizeof($rrule);$z++) {
							$rrule_n = explode("=", $rrule[$z]);
							switch($rrule_n[0]) {
								case "FREQ":
									$arr_n[$x]['type'] = $this->getConvertType($rrule_n[1], true);
									break;
										
								case "INTERVAL":
									$arr_n[$x]['count'] = $rrule_n[1];
									break;
										
								case "COUNT":
									$arr_n[$x]['extra'] = $rrule_n[1];
									break;
	
								case "BYDAY":
									$bayday = explode(",",$rrule_n[1]);
									if(sizeof($bayday) == 1) {
										if(strlen(trim($bayday[0])) == 3) {
											$arr_n[$x]['day'] = substr($bayday[0], 0, 1);
											$arr_n[$x]['counts'] = $this->getConvertDay(substr($bayday[0], 1, 2), true);
										}
										else {
											$arr_n[$x]['days'] = $this->getConvertDay($bayday[0], true);
										}
									}
									else {
										$arr_n[$x]['days'] = "";
										for($nx=0;$nx<sizeof($bayday);$nx++) {
											$arr_n[$x]['days'] .= $this->getConvertDay($bayday[$nx], true);
											if($nx != sizeof($bayday)-1) {
												$arr_n[$x]['days'] .= ",";
											}
										}
									}
									break;
										
								case "UNTIL":
									$arr_n[$x]['until'] = $this->getMySQLDate($rrule_n[1]);
									break;
							}
						}
						break;
	
					case "EXDATE":
						$exdate = explode(",",trim($mas[1]));
						if(sizeof($exdate) == 1) {
							$arr_n[$x]['exdate'] = $this->getMySQLDate($exdate[0]);
						}
						else {
							for($nx=0;$nx<sizeof($exdate);$nx++) {
								$arr_n[$x]['exdate'][$nx] = $this->getMySQLDate($exdate[$nx]);
							}
						}
						break;
							
					case "RECURRENCE-ID":
						$arr_n[$x]['rec_id'] = $this->getMySQLDate($mas[1]);
						break;
	
					case "UID":
						//$arr_n[$x]['event_id'] = $x;
						$arr_n[$x]['event_id'] = trim($mas[1]);
						break;
	
					case "SUMMARY":
						$arr_n[$x]['text'] = trim($mas[1]);
						break;
				}
			}
			if(isset($arr_n[$x]['rec_id'])){
				$arr_n[$x]['event_pid'] = $arr_n[$x]['event_id'];
			}
			if(isset($arr_n[$x]['exdate'])){
				$arr_n[$x]['event_pid'] = $arr_n[$x]['event_id'];
			}
		}
		return $arr_n;
	}
	
	function getNativeStartDate($arr_p) {
		if(isset($arr_p['day']) or isset($arr_p['days'])) {
			$odate = strtotime($arr_p['start_date']);
			switch($arr_p['type']) {
				case "week":
					$week_day = (date("N",$odate)-1)*60*60*24;
					$week_start = date("Y-m-d H:i:s", $odate - $week_day);
					$start_date = $week_start;
					break;
	
				case "month":
				case "year":
					$start_date = date("Y-m", $odate)."-01 ".date("H:i:s", $odate);
					break;
			}
		}
		else {
			$start_date = $arr_p['start_date'];
		}
		return $start_date;
	}
	
	function getSortArrayById($arr) {
		
		$id = 1;
		for($x=1;$x<=sizeof($arr);$x++){
			for($y=1;$y<=sizeof($arr);$y++){
				if($arr[$x]['event_id'] == $arr[$y]['event_pid'] and $arr[$x]['event_pid'] == "0" and $arr[$y]['event_pid'] != "0"){
					if($arr[$y]['rec_type'] == "" or $arr[$y]['rec_type'] == "none") {
						$arr[$y]['event_pid'] = $id;
					}
				}
			}
			$arr[$x]['event_id'] = $id;
			$id++;
		}
		
		//IDO
		foreach ($arr as $id => $event){
			if (!key_exists('start_date', $event)) unset($arr[$id]);
		}
		
		return $arr;
	}
	
	//return hashs
	function toHash($str, $maxBackDays = 5) {
		$minStartDate = strtotime('-' . $maxBackDays . ' day');
		
		//Try to get from URL
		if(strpos($str, "BEGIN:VCALENDAR") === false) $str = file_get_contents($str);

		$arr_p = $this->getParseString($str);

		$arr_n = array();
		$id = 1;
		for($i=1;$i<=sizeof($arr_p);$i++) {
			$startDate = strtotime($arr_p[$i]['start_date']);
			if (! $arr_p[$i]['type'] && ($minStartDate > $startDate)) continue;
			
			if(isset($arr_p[$i]['rec_id'])){
				$arr_n[$i]['event_id'] = $arr_p[$i]['event_id'];
				$arr_n[$i]['start_date'] = $arr_p[$i]['start_date'];
				$arr_n[$i]['end_date'] = $arr_p[$i]['end_date'];
				$arr_n[$i]['text'] = $arr_p[$i]['text'];
				$arr_n[$i]['rec_type'] = "";
				$arr_n[$i]['event_pid'] = $arr_p[$i]['event_pid'];
				$arr_n[$i]['event_length'] = strtotime($arr_p[$i]['rec_id']);
				
				if (key_exists('location', $arr_p[$i])) $arr_n[$i]['location'] = $arr_p[$i]['location'];
				if (key_exists('description', $arr_p[$i])) $arr_n[$i]['description'] = $arr_p[$i]['description'];
			}
			else {
				if(isset($arr_p[$i]['exdate'])){
					if(sizeof($arr_p[$i]['exdate'])> 1) {
						for($ni=0;$ni<sizeof($arr_p[$i]['exdate']);$ni++) {
							$arr_n[sizeof($arr_p)+$id]['event_id'] = $arr_p[$i]['event_id'];
							$arr_n[sizeof($arr_p)+$id]['start_date'] = $arr_p[$i]['exdate'][$ni];
							$arr_n[sizeof($arr_p)+$id]['end_date'] = date("Y-m-d H:i:s", strtotime($arr_p[$i]['exdate'][$ni])
									+(strtotime($arr_p[$i]['end_date']) - strtotime($arr_p[$i]['start_date'])));
							$arr_n[sizeof($arr_p)+$id]['text'] = "";
							$arr_n[sizeof($arr_p)+$id]['rec_type'] = "none";
							$arr_n[sizeof($arr_p)+$id]['event_pid'] = $arr_p[$i]['event_pid'];
							$arr_n[sizeof($arr_p)+$id]['event_length'] = strtotime($arr_p[$i]['exdate'][$ni]);
							$id++;
						}
					}
					else {
						$arr_n[sizeof($arr_p)+$id]['event_id'] = $arr_p[$i]['event_id'];
						$arr_n[sizeof($arr_p)+$id]['start_date'] = $arr_p[$i]['exdate'];
						$arr_n[sizeof($arr_p)+$id]['end_date'] = date("Y-m-d H:i:s", strtotime($arr_p[$i]['exdate'])
								+(strtotime($arr_p[$i]['end_date']) - strtotime($arr_p[$i]['start_date'])));
						$arr_n[sizeof($arr_p)+$id]['text'] = "";
						$arr_n[sizeof($arr_p)+$id]['rec_type'] = "none";
						$arr_n[sizeof($arr_p)+$id]['event_pid'] = $arr_p[$i]['event_pid'];
						$arr_n[sizeof($arr_p)+$id]['event_length'] = strtotime($arr_p[$i]['exdate']);
						$id++;
					}
				}
				//id
				$arr_n[$i]['event_id'] = $arr_p[$i]['event_id'];
	
				//start_date
				$arr_n[$i]['start_date'] = $this->getNativeStartDate($arr_p[$i]);
	
				//rec_type
				isset($arr_p[$i]['type'])? $type = $arr_p[$i]['type'] : $type = "";
				isset($arr_p[$i]['count'])? $count = $arr_p[$i]['count'] : $count = "";
				isset($arr_p[$i]['counts'])? $counts = $arr_p[$i]['counts'] : $counts = "";
				isset($arr_p[$i]['day'])? $day = $arr_p[$i]['day'] : $day = "";
				isset($arr_p[$i]['days'])? $days = $arr_p[$i]['days'] : $days = "";
				isset($arr_p[$i]['extra'])? $extra = $arr_p[$i]['extra'] : $extra = "no";
				if($type != "" and $count == "") {
					$count = 1;
				}
				if($type != "") {
					$arr_n[$i]['rec_type'] = $type."_".$count."_".$counts."_".$day."_".$days."#".$extra;
				}
				else {
					$arr_n[$i]['rec_type'] = "";
				}
	
				//end_date
				if(isset($arr_p[$i]['until'])){
					$arr_n[$i]['end_date'] = $arr_p[$i]['until'];
				}
				else {
					if($arr_n[$i]['rec_type'] == "") {
						if(isset($arr_p[$i]['end_date'])) {
							$arr_n[$i]['end_date'] = $arr_p[$i]['end_date'];
						}
						else {
							$arr_n[$i]['end_date'] =  date("Y-m-d H:i:s",strtotime($arr_n[$i]['start_date'])+24*60*60);
							$arr_p[$i]['end_date'] = $arr_n[$i]['end_date'];
						}
					}
					else {
						$arr_n[$i]['end_date'] = "9999-01-01 00:00:00";
					}
				}
				//text
				$arr_n[$i]['text'] = $arr_p[$i]['text'];
	
	
				//event_pid
				$arr_n[$i]['event_pid'] = "0";
	
				//event_length
				$arr_n[$i]['event_length'] = strtotime($arr_p[$i]['end_date']) - strtotime($arr_p[$i]['start_date']);
				
				if (key_exists('location', $arr_p[$i])) $arr_n[$i]['location'] = $arr_p[$i]['location'];
				if (key_exists('description', $arr_p[$i])) $arr_n[$i]['description'] = $arr_p[$i]['description'];
			}
		}
		
		return $this->getSortArrayById($arr_n);
	}
}


