<?php
	$read_changes_array = array('all_day_activated', 'all_day_minute_range_from', 'all_day_minute_range_to', 'hour_range_from', 'minute_range_from', 'hour_range_to', 'minute_range_to');
	$data = json_decode(stripslashes($_POST['data']));
	$error=0;
	$log="";
	
	
	$i=0;
	foreach($read_changes_array as $d){
		$$d=$data[$i];
		$i++;
	}
	
	$user_id 	= "control_tree";
	$passwd  	= "snowman11";
	$db_nm   	= "control_show_schedule";
	$host    	= "localhost";
	
	$link = mysql_connect($host, $user_id, $passwd) or die ("Error : " . mysql_error());
	$db = mysql_select_db($db_nm, $link) or die ("Error : " . mysql_error());
	
	// Create connection
	$conn = new mysqli($host, $user_id, $passwd, $db_nm);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
		
	$i=1;
	foreach($all_day_activated as $d){
		if($d != NULL && $d != 'NULL'){
			$d = explode("/", $d);
			
			$date = $d[2].'-'.$d[0].'-'.$d[1].' 00:00:00';
		
			$sql = "UPDATE active_days SET date = '".$date."' WHERE id=".$i.";";
			$result = $conn->query($sql);
		}
		else{
			$sql = "UPDATE active_days SET date = ".$d." WHERE id=".$i.";";
			$result = $conn->query($sql);
		}
		
		if ($conn->query($sql) === TRUE) {
			$log.="Record updated successfully";
		} else {
			$log.="Error updating record: " . $conn->error;
			$error++;
		}
	$i++;
	}
	
	$sql = "UPDATE active_days_time_ranges SET from_min = ".$all_day_minute_range_from[0].", to_min = ".$all_day_minute_range_to[0]."  WHERE id=1;";
	$result = $conn->query($sql);
	
	if ($conn->query($sql) === TRUE) {
		$log.="Record updated successfully";
	} else {
		$log.="Error updating record: " . $conn->error;
		$error++;
	}
	
	$i=1;
	foreach($hour_range_from as $d){
		$j=$i-1;
		
		if($d != NULL && $d != 'NULL'){
			if(($hour_range_from[$j] != NULL && $hour_range_from[$j] != 'NULL') && ($minute_range_from[$j] != NULL && $minute_range_from[$j] != 'NULL') && ($hour_range_to[$j] != NULL && $minute_range_to[$j] != 'NULL')){
				$from = $hour_range_from[$j].':'.$minute_range_from[$j].':00';
				$to = $hour_range_to[$j].':'.$minute_range_to[$j].':00';
				$sql = "UPDATE active_time_ranges SET from_time = '".$from."', to_time = '".$to."' WHERE id=".$i.";";
				$result = $conn->query($sql);
			}
			else{
				$sql = "UPDATE active_time_ranges SET from_time = NULL, to_time = NULL WHERE id=".$i.";";
				$result = $conn->query($sql);
			}
		}
		else{
			$sql = "UPDATE active_time_ranges SET from_time = NULL, to_time = NULL WHERE id=".$i.";";
			$result = $conn->query($sql);
		}
		
		if ($conn->query($sql) === TRUE) {
			$log.="Record updated successfully";
		} else {
			$log.="Error updating record: " . $conn->error;
			$error++;
		}
	$i++;
	}
	
	//echo $log;
	
	if(!$error){
		echo "All changes saved!";
	}
	else{
		echo "Saving failed: <br>".$log;
	}
	
	$conn->close();
  ?>