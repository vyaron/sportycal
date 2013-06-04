<?php
sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');

/**
 * Mailinglist
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    evento
 * @subpackage model
 * @author     Yaron Biton
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Mailinglist extends BaseMailinglist{
	private $events = null;
	
	public function getIntelUrl(){
		$url = sfConfig::get('app_domain_full');

		$url .= '/s/intel/s/mailinglist/a/open/l/email/v/1';
		$url .= '/p/' . $this->getPartnerId();
		
		if ($this->getCalId()) $url .= '/cl/' . $this->getCalId();
		else if ($this->getCtgId()) $url .= '/ctg/' . $this->getCtgId();

		return $url;
	}
	
	public function getHash(){
		$hash = array('id' => $this->getId());
		return base64_encode(json_encode($hash));
	}
	
	private function getflatEvents($events){
		$minStartTime = time();
		$maxStartTime = strtotime('+1 week');
		
		//$minStartTime = strtotime('2013-06-09');
		//$maxStartTime = strtotime('2013-07-05');

		$eventsHash = array();
		foreach ($events as $event){
			$eventHash = array();
			
			$eventHash['name'] = $event->getName();
			
			$startsAt 	= $event->getStartsAt();
			$endsAt 	= $event->getEndsAt();
			
			//TODO: support Recurring events! http://docs.dhtmlx.com/doku.php?id=dhtmlxscheduler:recurring_events
			/*
			$recType = $event->getRecType();
			if ($recType){
				$parts = explode('#', $recType);
			
				$pattern = explode('_', $parts);
				$extra = (isset($recType[1])) ? $recType[1] : null;
				
				$type = $pattern[0];
				
				switch ($type){
					case 'day':
						
						break;
					case 'week':
						
						break;
					case 'month':
						
						break;
					case 'year':
						
						break;
				}
			}
			*/
			
			$startsAtDt = GeneralUtils::getDateTimeInSpecificTZ($startsAt, $event->getTz(), $this->getTz());
			$endsAtDt 	= GeneralUtils::getDateTimeInSpecificTZ($endsAt, $event->getTz(), $this->getTz());
			
			$eventHash['starts_at'] = $startsAtDt->format('Y-m-d H:i:s');
			$eventHash['ends_at'] 	= $endsAtDt->format('Y-m-d H:i:s');
			
			$startTime = strtotime($eventHash['starts_at']);
			
			//if ($recType) Utils::pp($eventHash);
			if ($startTime >= $minStartTime && $startTime <= $maxStartTime) $eventsHash[] = $eventHash;
		}
		
		usort($eventsHash, 'Mailinglist::sortStartsAt');
		
		return $eventsHash;
	}
	
	public function getEvents(){
		if (!$this->events){
			$cal = $this->getCal();
			
			//get events between -1 day to +8 days (9 days) - for timezone claculation.
			$startDate = date('Y-m-d H:i:s', strtotime('-1 day'));
			$endsAt = date('Y-m-d H:i:s', strtotime('+8 day'));
			
			//Utils::pp($startDate . ' - ' . $endsAt);
			
			$q = Doctrine::getTable('Event')->createQuery('e')
			->where('e.cal_id = :calId', array(':calId' => $cal->getId()))
			->andWhere('(e.starts_at >= :startDate AND e.starts_at <= :endsAt)', array(':startDate' => $startDate, ':endsAt' => $endsAt));
			//->andWhere('(e.starts_at >= :startDate AND e.starts_at <= :endsAt) OR (e.rec_type IS NOT NULL)', array(':startDate' => $startDate, ':endsAt' => $endsAt));
			
			$this->events = $this->getflatEvents($q->execute());
		}
		
		return $this->events;
	}
	
	public function getHtmlMail(){
		return get_partial('mailinglist/weeklyEvents', array('mailinglist' => $this));
	}
	
	public function getTextMail(){
		$textMail = '';
		
		$cal = $this->getCal();
		
		$textMail .= $cal->getName() . ' Calendar' . Utils::NEW_LINE . Utils::NEW_LINE;
		
		$date = null;
		foreach ($this->getEvents() as $event){
			$dateParts = explode(' ', $event['starts_at']);
			
			if ($date != $dateParts[0]) {
				$date = $dateParts[0];
				$textMail .= Utils::NEW_LINE . $date . ':' . Utils::NEW_LINE;
			}
			
			$textMail .= $dateParts[1] . ' - ' . $event['name'] . Utils::NEW_LINE;
		}
		
		$textMail .= Utils::NEW_LINE . 'If you\'d like to unsubscribe and stop receiving ' . $cal->getName() . ' Calendar emails Unsubscribe:' .Utils::NEW_LINE;
		$textMail .= sfConfig::get('app_domain_full') . '/mailinglist/unsubscribe/h/' . $this->getHash();
		
		return $textMail;
	}
	
	public function send(){
		
		
		$mail = new PHPMailer();
		$mail->IsSMTP();
		
		$mail->SMTPDebug  = 0;
		//if (sfConfig::get('sf_debug')) $mail->SMTPDebug  = 3;
		//$mail->Debugoutput = 'html';
		
		//Gmail SMTP
		$mail->Host       = 'smtp.gmail.com';
		$mail->Port       = 587;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth   = true;

		$mail->Username   = sfConfig::get('app_gmail_username');
		$mail->Password   = sfConfig::get('app_gmail_password');

		$mail->SetFrom(sfConfig::get('app_mailinglist_fromEmail'), sfConfig::get('app_mailinglist_fromName'));
		$mail->AddReplyTo(sfConfig::get('app_mailinglist_replyToEmail'), sfConfig::get('app_mailinglist_replyToName'));

		$mail->AddAddress($this->getEmail(), $this->getFullName());
		

		$mail->Subject = $this->getFullName() . ', check out what\'s coming up this week';
		
		$mail->MsgHTML($this->getHtmlMail());
		
		//TODO: create text template
		$mail->AltBody = 'This is a plain-text message body';
		
		//$mail->AddAttachment('D:\WS\PHP\sportycal\lib\model\PHPMailer\examples\images\phpmailer_mini.gif');
		
		return $mail->Send();
	}
	
	public static function sortStartsAt($a, $b){
		return strtotime($a['starts_at']) - strtotime($b['starts_at']);
	}
}