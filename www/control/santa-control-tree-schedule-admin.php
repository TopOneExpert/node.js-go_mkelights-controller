<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link REL="SHORTCUT ICON" HREF="favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TheSantaTracker.com :: Santa's Controllable Christmas Tree</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script>
  $(function() {
    $( ".datepicker" ).datepicker();
  });
  
  function save_changes(){
	console.log("Saving started");

	//console.log("Always active: "+is_always_active);
	
	
	read_changes_array = ['all_day_activated', 'all_day_minute_range_from', 'all_day_minute_range_to', 'hour_range_from', 'minute_range_from', 'hour_range_to', 'minute_range_to'];
	output_array = [];
	for(j=0; j<read_changes_array.length; j++){
		data_array = document.getElementsByClassName(read_changes_array[j]);
	
		data_values='';
		for(i=0; i<data_array.length; i++){
			if(i+1 != data_array.length)	{
				data_value = data_array[i].value;
				if(data_value != "") data_values+= data_value + ', ';
				else data_values+=  'NULL, ';
			}
			else{
				data_value = data_array[i].value;
				if(data_value != "") data_values+= data_value;
				else data_values+=  'NULL';
			}
		}
		//console.log(read_changes_array[j] + ': ' +data_values);
		
		output_array[j]= data_values.split(', ');
		
	}
	
	//console.log(output_array);
	
	var jsonString = JSON.stringify(output_array);
   $.ajax({
        type: "POST",
        url: "process_schedule_change.php",
        data: {data : jsonString}, 
        cache: false,

        success: function(data){
            //console.log("Data sent");
			$("#save_status").html(data);
			
			setTimeout(function(){ $("#save_status").html(""); }, 5000);
        }
    });
  }
  </script>

</head>

<body>

	<b>Timer schedule</b><br/><br/>
	
	<?php
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
	
	//--------------------------------- Active in this days --------------------------------------------
	
	$sql = "SELECT * FROM active_days";
	$result = $conn->query($sql);
	
	$output="From: ";
	
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			if($row["id"] == 1) $output.= '<input name="all_day'.$row["id"].'" type="text" class="datepicker all_day_activated" size="8" ';
			else $output.= '<input name="all_day'.$row["id"].'" type="text" class="datepicker all_day_activated" size="8" ';
				
				if($row["date"] != NULL){
						$date = date("m/d/Y", strtotime($row["date"]));
						$output.= "value=\"".$date."\">";
				}
				else $output.= "value=\"\">";
				
				if($row["id"] == 1) $output.= ' 7PM <br /> To: &nbsp;&nbsp;&nbsp;&nbsp;';
				else $output.= ' 9PM';
			}	
	}
	
	?>
	Active in this days: <br/>
	<?php 
	$output.="<br/><br/>";
	
	$output.="In minute range, every hour: <br/>";
	$output.="From: ";
	
	$sql = "SELECT * FROM active_days_time_ranges";
	$result = $conn->query($sql);
	
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
	
	echo $output;
	//--------------------------------- Active everyday in time range --------------------------------------------
	
	
	?>
	Active everyday: <br/>
	<?php 
	
	$sql = "SELECT * FROM active_time_ranges";
	$result = $conn->query($sql);
	
	$output="";
	
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			
			$output.= 'From: ';
			
			//------------------------------------------------ Time range FROM ------------------------------------------------
			
			$from= explode(":", $row["from_time"]);
			$from_hour = $from[0];
			$from_minute = $from[1];
			
			$hours_list=$hour_not_set="";									
			for ($i=0; $i<24; $i++){
				if ($i<10)	$j = "0".$i;
				else $j = $i;
				if($from_hour != NULL){
					if($from_hour == $j) $selected = "selected=\"selected\"";
					else $selected = "";
				}
				else{
					$selected = "";
					$hour_not_set = "selected=\"selected\"";
				}
				
				$hours_list.="<option ".$selected." value=\"". $j ."\">". $j ."</option>";
			}
			$hours_list.="</select>";
			
			$minutes_list=$minute_not_set ="";
			for ($i=0; $i<60; $i++){
				if ($i<10)	$j = "0".$i;
				else $j = $i;
				if($from_minute != NULL){
					if($from_minute == $j) $selected = "selected=\"selected\"";
					else $selected = "";
				}
				else{
					$selected = "";
					$minute_not_set = "selected=\"selected\"";
				}
				
				$minutes_list.="<option ".$selected." value=\"". $j ."\">". $j ."</option>";
			}
			$minutes_list.="</select>";
	
			
			$output.= '<select class="hour_range_from" name="hour_range_from'.$row["id"].'"><option '.$hour_not_set .' value=""></option>'.$hours_list.' : ';
			$output.= '<select class="minute_range_from" name="minute_range_from'.$row["id"].'"><option '.$minute_not_set.' value=""></option>'.$minutes_list;
			
			//------------------------------------------------ Time range TO ------------------------------------------------
			
			$to= explode(":", $row["to_time"]);
			$to_hour = $to[0];
			$to_minute = $to[1];
			
			$hours_list=$hour_not_set="";									
			for ($i=0; $i<24; $i++){
				if ($i<10)	$j = "0".$i;
				else $j = $i;
				if($to_hour != NULL){
					if($to_hour == $j) $selected = "selected=\"selected\"";
					else $selected = "";
				}
				else{
					$selected = "";
					$hour_not_set = "selected=\"selected\"";
				}
				
				$hours_list.="<option ".$selected." value=\"". $j ."\">". $j ."</option>";
			}
			$hours_list.="</select>";
			
			$minutes_list=$minute_not_set ="";
			for ($i=0; $i<60; $i++){
				if ($i<10)	$j = "0".$i;
				else $j = $i;
				if($to_minute != NULL){
					if($to_minute == $j) $selected = "selected=\"selected\"";
					else $selected = "";
				}
				else{
					$selected = "";
					$minute_not_set = "selected=\"selected\"";
				}
				
				$minutes_list.="<option ".$selected." value=\"". $j ."\">". $j ."</option>";
			}
			$minutes_list.="</select>";
	
			
			$output.= ' To: <select class="hour_range_to" name="hour_range_to'.$row["id"].'"><option '.$hour_not_set .' value=""></option>'.$hours_list.' : ';
			$output.= '<select class="minute_range_to" name="minute_range_to'.$row["id"].'"><option '.$minute_not_set.' value=""></option>'.$minutes_list.'<br/>';
		}
	}
		echo $output.'<br />';
	
	
	$conn->close();
	?>

	<br/>
	<button type="button" onclick="save_changes()">Save</button>
	<div id="save_status"></div>
						
</body>
</html>
