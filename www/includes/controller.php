<?php
/* @var $start_hour /includes/settings.php */
/* @var $end_hour /includes/settings.php */

include "settings.php";
include_once './model/Scheduler.php';

use model\Scheduler;

$scheduler = new Scheduler();
$stateChangeCountdown = false;

$startDate = $scheduler->eventStartDateTimestamp;
$endDate = $scheduler->eventEndDateTimestamp;

$checkBackDate = date("F d", $scheduler->eventStartDateTimestamp);

$marathonStart = $scheduler->marathonStartTimestamp;
$marathonEnd = $scheduler->marathonEndTimestamp;

$start_hour = DateTime::createFromFormat('H:i', $scheduler->startTime)->format('H');
$end_hour = DateTime::createFromFormat('H:i', $scheduler->endTime)->format('H');

date_default_timezone_set('US/Central');

//$start_hour = 17;
//$end_hour = 22;


//$currentTimestamp = time();
$currentDate = $date = new DateTime(null, new DateTimeZone('US/Central'));
$currentTimestamp = $currentDate->getTimestamp() + $date->getOffset();

$todayStartTimestamp = strtotime(date('Y-m-d'));
$tomorrowStartTimestamp = strtotime(date('Y-m-d', strtotime('+1 day', time())));

$hour = intval(date("H"));

$currentHourAndMinute = intval(date("H")) * 3600 + intval(date("i")) * 60;

$startTimestamp = $todayStartTimestamp + $scheduler->startTimeTimestamp + $date->getOffset();

if ($scheduler->startTimeTimestamp < $scheduler->endTimeTimestamp)
	$endTimestamp = $todayStartTimestamp + $scheduler->endTimeTimestamp + $date->getOffset();
elseif ($currentTimestamp > $startTimestamp)
	$endTimestamp = $tomorrowStartTimestamp + $date->getOffset();
elseif ($currentTimestamp < $startTimestamp)
	$endTimestamp = $todayStartTimestamp + $scheduler->endTimeTimestamp + $date->getOffset();

$offlineText = '';

$m_dt = date('g:ia');

if ($start_hour > 12) $display_start_hour = ($start_hour - 12) . 'pm';
else $display_start_hour = $start_hour . 'am';

if ($end_hour > 12) $display_end_hour = ($end_hour - 12) . 'pm';
else $display_end_hour = $end_hour . 'am';

if ($currentTimestamp >= $startDate && $currentTimestamp < $endDate)
{
	// to check marathon: ($currentTimestamp >= $marathonStart && $currentTimestamp < $marathonEnd)
	if (($scheduler->startTimeTimestamp < $scheduler->endTimeTimestamp && $currentTimestamp >= $startTimestamp && $currentTimestamp < $endTimestamp) || ($scheduler->startTimeTimestamp > $scheduler->endTimeTimestamp && ($currentTimestamp >= $startTimestamp || $currentTimestamp < $endTimestamp)))
	{
		$opened = 1;

		$stateChangeCountdown = $endTimestamp - $currentTimestamp;
	}
	else
	{
		$opened = 0;
		$offlineText = "Offline - Check back at $display_start_hour CST!";

		if ($startTimestamp < $endTimestamp && $currentTimestamp < $startTimestamp)
			$stateChangeCountdown = $startTimestamp - $currentTimestamp;
		elseif ($startTimestamp < $endTimestamp && $currentTimestamp > $endTimestamp)
			$stateChangeCountdown = $tomorrowStartTimestamp + $scheduler->startTimeTimestamp + $date->getOffset() - $currentTimestamp;
		else
			$stateChangeCountdown = $startTimestamp - $currentTimestamp;
	}
}
else
{
	$opened = 0;

	if ($currentTimestamp < $startDate) $offlineText = "Offline - Check back on $checkBackDate!" ;
	elseif ($currentTimestamp > $startDate) $offlineText = "Offline - Check back next year!" ;
}

//echo $hour;

