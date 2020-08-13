<?php
	date_default_timezone_set('UTC');
	
	$data = urldecode($_GET['data']);
	$seed = date("Y-m-d H:i:s", ($_GET['seed']/1000));
	
	$server_time = time() - 6*60*60;
	$current_date = date("Y-m-d H:i:s", $server_time);
	$file = 'countdown-log.txt';
	
	
	$current = file_get_contents($file);
	$current .= 'Server time = '.$current_date. " ||| User time =  ".$seed." ||| Data: ".$data." \r\n";
	file_put_contents($file, $current);
	
  ?>