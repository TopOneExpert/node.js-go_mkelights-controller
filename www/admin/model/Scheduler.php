<?php
namespace admin\model;

include_once '../model/Scheduler.php';

use DateTime;
use \model\Scheduler as BaseScheduler;

class Scheduler extends BaseScheduler
{
	public function getActiveDaysTimeRange() {
		$sql = "SELECT * FROM active_days_time_ranges";
		$result = $this->db->query($sql);

		$output = '';

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$from_minute = $row["from_min"];
				$to_minute = $row["to_min"];

				$minutes_list="";

				for ($i=0; $i<60; $i++){
					if ($i<10)	$j = "0".$i;
					else $j = $i;
					if($from_minute == $i) $selected = "selected=\"selected\"";
					else $selected = "";

					$minutes_list.="<option ".$selected." value=\"". $i ."\">". $j ."</option>";
				}
				$minutes_list.="</select>";

				$output.= '<select class="all_day_minute_range_from" name="all_day_from_min">'.$minutes_list;

				$minutes_list="";

				for ($i=0; $i<60; $i++){
					if ($i<10)	$j = "0".$i;
					else $j = $i;
					if($to_minute == $i) $selected = "selected=\"selected\"";
					else $selected = "";

					$minutes_list.="<option ".$selected." value=\"". $i ."\">". $j ."</option>";
				}
				$minutes_list.="</select>";

				$output.= ' To: <select class="all_day_minute_range_to" name="all_day_to_min">'.$minutes_list;


				//$output.= '<input name="all_day_from_min" type="text" class="datepicker all_day_activated" size="8" value="'.$row["from_min"].'" >';
				//$output.= ' To: <input name="all_day'.$row["id"].'" type="text" class="all_day_activated" size="8" value="'.$row["to_min"].'" >';
			}
		}
		$output.="<br/><br/><br/>";

		return $output;
	}

	public function saveSchedule($data) {
		$formattedData = [];

		$error=0;
		$log = [];

		foreach ($data as $input)
		{
			$formattedData[$input->name] = $input->value;
		}

		$eventStartDate = DateTime::createFromFormat( 'd/m/Y', $formattedData['eventStartDate']);
		$eventEndDate = DateTime::createFromFormat( 'd/m/Y', $formattedData['eventEndDate']);

		$this->eventStartDate = $eventStartDate->format('Y-m-d');
		$this->eventEndDate = $eventEndDate->format('Y-m-d');

		$this->startTime = $formattedData['startTime'];
		$this->endTime = $formattedData['endTime'];

		$marathonStart = DateTime::createFromFormat( 'd/m/Y H:i', $formattedData['marathonStart']);
		$marathonEnd = DateTime::createFromFormat('d/m/Y H:i', $formattedData['marathonEnd']);

		$this->marathonStart = $marathonStart->getTimestamp();
		$this->marathonEnd = $marathonEnd->getTimestamp();

		$sql = "UPDATE event_activation_time_range SET event_start = '".$this->eventStartDate."', event_end = '".$this->eventEndDate."'  WHERE id=1;";
		$log['saving_event_dates'] = $this->db->query($sql);

		$sql = "UPDATE active_time_ranges SET from_time = '".$this->startTime."', to_time = '".$this->endTime."'  WHERE id=1;";
		$log['saving_timings'] = $this->db->query($sql);

		$sql = "UPDATE marathon_time_range SET marathon_start = FROM_UNIXTIME('".$this->marathonStart."'), marathon_end = FROM_UNIXTIME('".$this->marathonEnd."')  WHERE id=1;";
		$log['saving_marathon_time_range'] = $this->db->query($sql);


		foreach ($log as $name => $value)
		{
			if (!$value) {
				$error = ucfirst(str_replace('_', ' ', $name));
				return 'Saving failed. Error in: ' . $error;
			}
		}

		return "All changes saved!";
	}
}