<?php
include_once 'model/Scheduler.php';

use admin\model\Scheduler;

$data = json_decode(stripslashes($_POST['data']));

$scheduler = new Scheduler;

echo $scheduler->saveSchedule($data);


