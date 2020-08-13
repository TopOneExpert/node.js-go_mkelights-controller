<?php
include_once('atomic_time.php');
date_default_timezone_set("America/Chicago");
$date_modifier = 0;
//$date_modifier = 11*60*60;

$server_name = "localhost";
$user_name = "control_tree";
$user_pass = "snowman11";
$db_name = "control_show_schedule";

$link = mysql_connect($server_name, $user_name, $user_pass) or die ("Error : " . mysql_error());
$db = mysql_select_db($db_name, $link) or die ("Error : " . mysql_error());

// Create connection
$conn = new mysqli($server_name, $user_name, $user_pass, $db_name);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM atomic_time";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	// output data of each row
	while($row = $result->fetch_assoc()) {
		$database_atomic =  strtotime($row['atomic_timestamp']);
		$database_server =  strtotime($row['server_timestamp']);
	}
	$diff_atom_server = $database_server - $database_atomic;
	$current = time();
	$time_passed = $current - $database_server;
}
//---------------------------------------------------------------------------------
$atomic_refresh_time = 6*60*60;
//---------------------------------------------------------------------------------

if ($time_passed > $atomic_refresh_time){
	try {

			$atomic_time = atomicTime();
			$atomic_to_db = date('Y-m-d H:i:s', $atomic_time);
			$current_to_db = date('Y-m-d H:i:s', $current);
			$sql = "UPDATE atomic_time SET atomic_timestamp = '".$atomic_to_db."', server_timestamp= '".$current_to_db."' WHERE id=1;";
			$result = $conn->query($sql);
			
	}
	catch(Exception $e)	{  }
}
else {
	$atomic_time = time() - $diff_atom_server;
}

$server_time = $atomic_time +$date_modifier;

$current_date = date('Y-m-d', $server_time);
//$server_time = time();
$current_timestamp = $server_time;
//$next_midnight= date('G', $server_time);
$next_midnight = strtotime($current_date .' 00:00:00');

//---------------------------------------------------------------------------------
$countdown = 0;
$output = "";
$first_spec_show_hour= 19;
$last_spec_show_hour = 21;
//---------------------------------------------------------------------------------


$sql = "SELECT * FROM active_days";
$result = $conn->query($sql);
$i=0;
$active_today = 0;

if ($result->num_rows > 0) {
	// output data of each row
	while($row = $result->fetch_assoc()) {
		if($row['id']==1) $spec_day_from = strtotime($row['date']);
		elseif ($row['id']==2) $spec_day_to = strtotime($row['date']);
	
	}
		
		if($spec_day_from<$current_timestamp  && ($spec_day_to+23*60*60+59*60+59)>$current_timestamp) $spec_day=1;
		else $spec_day=0;
}



$sql = "SELECT * FROM active_time_ranges";
$result = $conn->query($sql);
$i=0;
			
if ($result->num_rows > 0) {
	// output data of each row
	while($row = $result->fetch_assoc()) {
		if($row['from_time'] != NULL && $row['to_time'] != NULL){			
			$active_time_ranges[$i][0]=strtotime($current_date .' '.$row['from_time']) + 6*60*60;
			$active_time_ranges[$i][1]=strtotime($current_date .' '.$row['to_time']) + 6*60*60;
			
			$next_start_temp = $active_time_ranges[$i][0];
			
			$next_stop_temp = $active_time_ranges[$i][1];
			$last_stop_temp = $active_time_ranges[$i][1] ;
			

			$next_start_temp_if = $active_time_ranges[$i][0]-$current_timestamp-6*60*60;
			
			$next_stop_temp_if = $active_time_ranges[$i][1]-$current_timestamp-6*60*60;
			$last_stop_temp_if = $active_time_ranges[$i][1] -$current_timestamp-6*60*60;
			
			//--------------------------------------------------- next start -----------------------------------------------------
			if(($next_start_temp_if > 0 && $next_start_if > $next_start_temp_if && $next_start_temp_if != 0) || ($next_start == '' && $next_start_temp_if>0)){
				$next_start = $next_start_temp;
				$next_start_if = $next_start_temp_if;
			}
			
			//--------------------------------------------------- Find current and next stop ---------------------------------------------------
			if(($next_stop_temp_if > 0 && $next_stop_if > $next_stop_temp_if && $next_stop_temp_if != 0) || ($next_stop_if == '' && $next_stop_temp_if>0)){
				$next_stop = $next_stop_temp;
				$next_stop_if = $next_stop_temp_if;
			}
			
			//--------------------------------------------------- Find last stop ---------------------------------------------------
			if(($last_stop_temp_if > 0 && $last_stop_if < $last_stop_temp_if && $last_stop_temp_if != 0) || $i==0){
				$last_stop = $last_stop_temp;
				$last_stop_if = $last_stop_temp_if;
			}
		}
		$i++;
	}
}


if($spec_day){
	$active_time_ranges = '';
	$first_spec_show = $spec_day_from + $first_spec_show_hour * 60*60;
	$last_spec_show = $spec_day_to + $last_spec_show_hour*60*60;
	
	$sql = "SELECT * FROM active_days_time_ranges";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$spec_min_from = $row['from_min'];//3
			$spec_min_to = $row['to_min'];//7
		}
	}
	if ($spec_min_from<10)	$spec_min_from = "0".$spec_min_from;
	if ($spec_min_to<10)	$spec_min_to = "0".$spec_min_to;
	
	$current_day = date('j', $current_timestamp);
	$first_spec_day = date('j', $first_spec_show);
	$last_spec_day = date('j', $last_spec_show);
	
	if($current_day == $first_spec_day){
	$spec_day_type = 'First day';
		for ($i=0; $i<25-$first_spec_show_hour; $i++){
			$j = $i + $first_spec_show_hour;

			$active_time_ranges[$i][0] = strtotime($current_date .' '.$j.':'.$spec_min_from.':00')+ 6*60*60;
			$active_time_ranges[$i][1] = strtotime($current_date .' '.$j.':'.$spec_min_to.':00')+ 6*60*60;
		}
	}
	elseif($current_day == $last_spec_day){
	$spec_day_type = 'Last day';
		for ($i=0; $i<$last_spec_show_hour+1; $i++){
				if ($i<10) $j = "0".$i;
				else $j=$i;
				
				$active_time_ranges[$i][0] = strtotime($current_date .' '.$j.':'.$spec_min_from.':00')+ 6*60*60;
				$active_time_ranges[$i][1] = strtotime($current_date .' '.$j.':'.$spec_min_to.':00')+ 6*60*60;
		}
	}
	else{
	$spec_day_type = 'Whole day';
		for ($i=0; $i<25; $i++){
				if ($i<10) $j = "0".$i;
				else $j=$i;
				
				$active_time_ranges[$i][0] = strtotime($current_date .' '.$j.':'.$spec_min_from.':00')+ 6*60*60;
				$active_time_ranges[$i][1] = strtotime($current_date .' '.$j.':'.$spec_min_to.':00')+ 6*60*60;
			}
	}
		
		
	for($i=0; $i<sizeof($active_time_ranges); $i++){
		$next_start_temp = $active_time_ranges[$i][0];
			
			$next_stop_temp = $active_time_ranges[$i][1];
			$last_stop_temp = $active_time_ranges[$i][1] ;
			

			$next_start_temp_if = $active_time_ranges[$i][0]-$current_timestamp-6*60*60;
			
			$next_stop_temp_if = $active_time_ranges[$i][1]-$current_timestamp-6*60*60;
			$last_stop_temp_if = $active_time_ranges[$i][1] -$current_timestamp-6*60*60;
			
			//--------------------------------------------------- next start -----------------------------------------------------
			if(($next_start_temp_if > 0 && $next_start_if > $next_start_temp_if && $next_start_temp_if != 0) || ($next_start == '' && $next_start_temp_if>0)){
				$next_start = $next_start_temp;
				$next_start_if = $next_start_temp_if;
			}
			
			//--------------------------------------------------- Find current and next stop ---------------------------------------------------
			if(($next_stop_temp_if > 0 && $next_stop_if > $next_stop_temp_if && $next_stop_temp_if != 0) || ($next_stop_if == '' && $next_stop_temp_if>0)){
				$next_stop = $next_stop_temp;
				$next_stop_if = $next_stop_temp_if;
			}
			
			//--------------------------------------------------- Find last stop ---------------------------------------------------
			if(($last_stop_temp_if > 0 && $last_stop_if < $last_stop_temp_if && $last_stop_temp_if != 0) || $i==0){
				$last_stop = $last_stop_temp;
				$last_stop_if = $last_stop_temp_if;
			}
	
	}
	
	
}


if($next_start == '') {
	if($next_stop == '') {
		$tomorrow = 1;
		$countdown = 1;
	}
	else{

	}
}
else{
	$tomorrow = 0;
	if($next_start < $next_stop) $countdown = 1;
	else $countdown = 0;
}

if($countdown) $output = '';
else	$output = "now playing";

$modifier = 6
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Online - Christmas Tree</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="author" content="" />
<link rel="stylesheet" type="text/css" href="style2.css" media="screen" />
<!-- <script src="js/jquery-1.2.6.min.js" type="text/javascript"></script> -->
<script src="js/countdown_tree_control.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>


<script>
	window.onload = function() {
		last_stop = parseInt(<?php echo $tomorrow; ?>);
		is_countdown_active = <?php echo $countdown; ?>;
		is_running = '<?php echo $output; ?>';
		spec_day = <?php echo $spec_day; ?>;
		/*
		console.log('Spec day: <?php echo $spec_day_type; ?> ');
		console.log('Server time: <?php echo date('Y-m-d H:i:s', time()); ?> ');
		console.log('Modified server time: <?php echo date('Y-m-d H:i:s', $current_timestamp); ?> ');
		console.log('Atomic time: <?php echo date('Y-m-d H:i:s', $atomic_time); ?> ');
		console.log('Spec first show: <?php echo $first_spec_show; ?> ');
		console.log('Spec last show: <?php echo $last_spec_show; ?> ');
		*/
			if(is_countdown_active && is_running == ''){
			
				if(last_stop == '0'){
					timer_status = '';
				
					next_start_timestamp = parseInt(<?php echo $next_start-$current_timestamp-$modifier*60*60; ?>);
					next_start = new Date(next_start_timestamp*1000);
					user_timezone = new Date().getTimezoneOffset();
					
					timer = document.getElementById("timer_status");
					
					server_time = new Date((<?php echo $current_timestamp; ?> + user_timezone*60)*1000);
					now_timestamp = new Date().getTime();
					deadline = new Date(now_timestamp + next_start_timestamp*1000);
					
					<?php $next_start = $next_start -$modifier*60*60; ?>
					server_deadline_date = new Date((<?php echo $next_start; ?> + user_timezone*60)*1000);
					user_time = new Date(now_timestamp);
					countdown_for_reload = next_start_timestamp *1000;
					
					
					/*
					console.log('Current server time:'+server_time);
					console.log('Server deadline date:'+server_deadline_date);
					console.log('User timezone offset:'+user_timezone);
					console.log('Current user time:'+user_time);
					console.log('Deadline date:'+deadline);
					console.log('Next midnight:'+<?php echo $next_midnight; ?>);
					console.log('Next start:'+<?php echo $next_start; ?>);
					console.log('Next stop:'+<?php echo $next_stop; ?>);
					console.log('Last stop:'+<?php echo $last_stop; ?>);*/
					
					
					timer.innerHTML = countdown(deadline, null, countdown.HOURS | countdown.MINUTES | countdown.SECONDS ).toString();
					the_countdown = setInterval(function(){
						timer.innerHTML = countdown(deadline, null, countdown.HOURS | countdown.MINUTES | countdown.SECONDS ).toString();
						if(timer.innerHTML == ' ' || timer.innerHTML == '')  {
							window.clearInterval(the_countdown);
							location.reload();
						}
					}, 1000);
					
					setInterval(function(){
						countdown(deadline, null, countdown.HOURS | countdown.MINUTES | countdown.SECONDS ).toString();
							window.clearInterval(the_countdown);
							location.reload();
					}, countdown_for_reload);
					
				}
				else{
					timer_status = 'tomorrow night';
					$('#timer_status').html(timer_status);
					
					user_timezone = new Date().getTimezoneOffset();
					next_start_timestamp = parseInt(<?php echo $next_start-$current_timestamp-$modifier*60*60; ?>);
					server_time = new Date((<?php echo $current_timestamp; ?> + user_timezone*60)*1000);
					server_deadline_date = new Date((<?php echo $next_start; ?> + user_timezone*60)*1000);
					now_timestamp = new Date().getTime();
					user_time = new Date(now_timestamp);
					deadline = new Date(now_timestamp + next_start_timestamp*1000);
					
					/*
					console.log('Current server time:'+server_time);
					console.log('Server deadline date:'+server_deadline_date);
					console.log('User timezone offset:'+user_timezone);
					console.log('Current user time:'+user_time);
					console.log('Deadline date:'+deadline);
					*/
				}
				  
				
			}
			else	{
				timer_status = '<?php echo $output; ?>';
				$('#timer_status').html(timer_status);
					<?php $stop = $next_stop - $current_timestamp-$modifier*60*60; ?>
					next_stop_timestamp = parseInt(<?php echo $stop; ?>);
					next_stop = new Date(next_stop_timestamp*1000);
					
					now_timestamp = new Date();
					deadline = next_stop_timestamp*1000;

					console.log(deadline);
					setInterval(function(){
						location.reload();
					}, deadline);
			}
		

	};
	
</script>

</head>

<body>

	<div id="santa-place-control-tree-button-area-header">Next Christmas Light Show is in: <span id="timer_status"></span></div>	

</div>
</body>
</html>
