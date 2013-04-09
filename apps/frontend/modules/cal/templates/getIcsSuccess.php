<?php
//////header('Content-Disposition: attachment; filename="sportYcal_'.substr(str_replace(' ', '_', $cal->getName()),0,20).'.ics"');
header('Content-type: text/calendar');
header('Content-Disposition: attachment; filename="sportYcal_'.str_replace(' ', '_', $cal->getName()).'.ics"');
?>
<?php 
$count = 1;
$events = $cal->getEventsForIcal($userCal, $calType);
?>
BEGIN:VCALENDAR
VERSION:2.0
CALSCALE:GREGORIAN
X-WR-TIMEZONE;VALUE=TEXT:US/Pacific
METHOD:PUBLISH
X-WR-CALNAME;VALUE=TEXT:<?php echo GeneralUtils::icalEscape($cal->getName())."\n"?>
VERSION:2.0
<?php foreach ($events as $event): ?>
BEGIN:VEVENT
UID:<?php echo $event->getIdForIcal()."\n"?>
DTSTART:<?php echo $event->getStartsAtForCal()."\n"?>
DTEND:<?php echo $event->getEndsAtForCal()."\n"?>
<?php if ($cal->isBirthdayCal()) :?>
RRULE:FREQ=YEARLY
<?php endif ?>
SUMMARY:<?php echo GeneralUtils::icalEscape($event->getNameForVcal($count))."\n"?>
DESCRIPTION: <?php echo GeneralUtils::icalEscape2($event->getDescriptionForCal($cal, $userCal, $partner, $calType, $intelLabel, $intelValue, null, ESC_RAW))."\n"?>
LOCATION: <?php echo GeneralUtils::icalEscape($event->getLocation())."\n"?>
<?php if ($event->hasHour() && $userCal && $userCal->getReminder() > 0) : ?>
BEGIN:VALARM
TRIGGER:-PT<?php echo ($userCal->getReminder() * 60)?>M
ACTION:DISPLAY
DESCRIPTION: Are you ready for the game?
END:VALARM
<?php endif ?>
END:VEVENT
<?php $count++?>
<?php endforeach; ?>
END:VCALENDAR