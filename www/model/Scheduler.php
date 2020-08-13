<?php

namespace model;

use DateTime;

class Scheduler
{
	private $host    	= "localhost";
	private $user_id 	= "santa_mkelights";
	private $passwd  	= "K?aF@csJb4X5";
	private $db_nm   	= "santa_mkelights_control_schedule";
//	private $host    	= "localhost";
//	private $user_id 	= "root";
//	private $passwd  	= "123";
//	private $db_nm   	= "control_schedule";

	public $db;

	public $eventStartDate;
	public $eventEndDate;

	public $startTime;
	public $endTime;

	public $marathonStart;
	public $marathonEnd;

	public $eventStartDateTimestamp;
	public $eventEndDateTimestamp;

	public $startTimeTimestamp;
	public $endTimeTimestamp;

	public $marathonStartTimestamp;
	public $marathonEndTimestamp;


	public function __construct() {
		$this->connectDb();

		if (!isset($_POST['data']))
		{
			$this->setEventDateRange();
			$this->setActiveTimeRanges();
			$this->setMarathonTimeRange();
		}
	}

	private function connectDb() {
		$this->db = mysqli_connect($this->host, $this->user_id, $this->passwd, $this->db_nm);

		// Check connection
		if ($this->db->connect_error) {
			die("Connection failed: " . $this->db->connect_error);
		}
	}

	public function setEventDateRange()
	{
		$sql = "SELECT * FROM event_activation_time_range";
		$result = $this->db->query($sql);

		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				$this->eventStartDateTimestamp = strtotime($row['event_start']);
				$this->eventEndDateTimestamp = strtotime($row['event_end']);

				$this->eventStartDate = date("d/m/Y", strtotime($row['event_start']));
				$this->eventEndDate = date("d/m/Y", strtotime($row['event_end']));
			}
		}
		else
		{
			$this->eventStartDateTimestamp = null;
			$this->eventEndDateTimestamp = null;
			$this->eventStartDate = '';
			$this->eventEndDate = '';
		}
	}

	public function setMarathonTimeRange() {
		$sql = "SELECT * FROM marathon_time_range";
		$result = $this->db->query($sql);

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				if($row["marathon_start"])
				{
//					date_default_timezone_set('CST6CDT');
					$this->marathonStartTimestamp = strtotime($row["marathon_start"]);
					$this->marathonStart = date("d/m/Y H:i", strtotime($row["marathon_start"]));
				}
				else
				{
					$this->marathonStartTimestamp = null;
					$this->marathonStart = '';
				}

				if($row["marathon_start"])
				{
//					date_default_timezone_set('CST6CDT');
					$this->marathonEndTimestamp = strtotime($row["marathon_end"]);
					$this->marathonEnd = date("d/m/Y H:i", strtotime($row["marathon_end"]));
				}
				else
				{
					$this->marathonEndTimestamp = null;
					$this->marathonEnd = '';
				}

			}
		}
	}

	public function setActiveTimeRanges() {
		$sql = "SELECT * FROM active_time_ranges";
		$result = $this->db->query($sql);

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {

				if ($row["from_time"])
				{
					$date = DateTime::createFromFormat('H:i:s', $row['from_time']);
					$hour = $date->format('H') * 3600;
					$minute = $date->format('i') * 60;

					$timestamp = $hour + $minute;

					$this->startTimeTimestamp = $timestamp;
					$this->startTime = date("H:i", strtotime($row["from_time"]));
				}
				else
				{
					$this->startTimeTimestamp = null;
					$this->startTime = '';
				}

				if ($row["to_time"])
				{
					$date = DateTime::createFromFormat('H:i:s', $row['to_time']);
					$hour = $date->format('H') * 3600;
					$minute = $date->format('i') * 60;

					$timestamp = $hour + $minute;

					$this->endTimeTimestamp = $timestamp;
					$this->endTime = date("H:i", strtotime($row["to_time"]));
				}
				else
				{
					$this->endTimeTimestamp = null;
					$this->endTime = '';
				}
			}
		}
	}
}