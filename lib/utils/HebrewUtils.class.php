<?php


class HebrewUtils {

	const TOTO_NONE_GAME1 = "אנדר/אובר";
	//const TOTO_NONE_GAME2 = "1 מחצית";
	//const TOTO_NONE_GAME3 = "קרן ראשונה";
	//const TOTO_NONE_GAME4 = "חילוף ראשון";
	//const TOTO_NONE_GAME5 = "יותר קרנות";
	//const TOTO_SEND_TOFES 		= "שלח טופס http://winner.co.il";
	//const TOTO_SEND_TOFES_HTML 	= "<a href='http://winner.co.il' target='_blank'><img src='http://www.sportYcal.com/images/partner/totoSend.png' alt='שלח טופס' title='שלח טופס'/></a>";
	
	public static function getDescForWinnerGame($game) {
		$eventDesc = "\n\n";
		
		$dateStr = format_date($game->event_time->__toString(), 'D') . ' ' . date('H:i', strtotime($game->event_time->__toString()));
		
		$eventDesc .= $dateStr . "\n\n";
		
		if ((float) trim($game->rate_1) >= 0) $eventDesc .= $game->homeTeamName . ' - ' . $game->rate_1 . "\n\n";
		if ((float) trim($game->rate_x) >= 0) $eventDesc .= 'תיקו' . ' - ' . $game->rate_x . "\n\n";
		if ((float) trim($game->rate_2) >= 0) $eventDesc .= $game->gestTeamName . ' - ' . $game->rate_2 . "\n\n";
		
		$eventDesc .= 'היחסים עשויים להשתנות' . "\n\n";
		
		if (!empty($game->eventRemarks)) $eventDesc .= 'הערות: ' .  $game->eventRemarks ."\n\n";
		
		$eventDesc .= 'שלח טופס' . ' - ' . 'http://www.sportycal.com/l/winner' . "\n\n";
		$eventDesc .= 'שירות לקוחות' . ' - ' . '*6040' . "\n\n";
		
		return $eventDesc;
	}
	
	public static function getDescForWinnerCtg() {
		$desc = '<div class="ctgDesc"><div class="ctgDescPadding"><div id="descForWinnerCtg">';
		$desc .= "<p>מהיום ניתן להוריד בחינם את כל אירועי הספורט שקרובים לליבכם ללוח השנה שלכם:</p>";
		$desc .= "<ul>";
		$desc .= "<li>בוחרים ענף, בוחרים ליגה/מפעל ומורידים ליומן האישי</li>";
		$desc .= "<li>המידע על אירועי הספורט מתעדכן באופן שוטף גם לאחר ההורדה ליומן והוא כולל גם יחסים מעודכנים</li>";
		$desc .= '</ul></div></div></div>';
		
		return $desc;
	}

	
	/*
	public static function getDescForWinnerGameMottyYohpazVersion($game) {
		$eventDesc = ":WINNER יחסי זכייה\nיום {$game->DayName} {$game->date}\n{$game->leagueName}\nשעת סגירה {$game->closingTime}\nמשחק מספר {$game->pos}\n\n{$game->name}\nסימון 1 - {$game->rate1}\n סימון איקס - {$game->ratex}\n  סימון 2 - {$game->rate2}\n{$game->Remarks} - הערות\n\nhttp://www.winner.co.il/lineForm.asp - !שלח טופס עכשיו \nhttp://www.winner.co.il/newuser.asp - לקוח חדש? עדין לא פתחת חשבון? \n\nלעזרה פנה למוקד שירות הלקוחות של הטוטו - חייג 6040*\n ההשתתפות אסורה למי שטרם מלאו לו 18\n";
		
		return $eventDesc;
	}*/
	
	
}




?>