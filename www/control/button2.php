<?php
	session_start();

    header("Expires: Tue, 28 Aug 2007 12:34:56 GMT ");

    require_once 'db.php';
    
	include 'config.php';
    

    	
    //echo " $hour : $start_hour : $end_hour : $opened";
include_once('atomic_time.php');
date_default_timezone_set("America/Chicago");
$date_modifier = 0;
//$date_modifier = 15*60*60+34*60;

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
			if ($atomic_time >0){
				$atomic_to_db = date('Y-m-d H:i:s', $atomic_time);
				$current_to_db = date('Y-m-d H:i:s', $current);
				$sql = "UPDATE atomic_time SET atomic_timestamp = '".$atomic_to_db."', server_timestamp= '".$current_to_db."' WHERE id=1;";
				$result = $conn->query($sql);
			}
			else $atomic_time = time() - $diff_atom_server;
	}
	catch(Exception $e)	{  }
}
else $atomic_time = time() - $diff_atom_server;


$server_time = $atomic_time +$date_modifier;

$current_date = date('Y-m-d', $server_time);
//$server_time = time();
$current_timestamp = $server_time;
$next_midnight = strtotime($current_date .' 00:00:00')+60*60*24;

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
		$start_hour = $first_spec_show_hour-1;
		$end_hour = 24;
		$spec_day_type = 'First day';
		for ($i=0; $i<25-$first_spec_show_hour; $i++){
			$j = $i + $first_spec_show_hour;

			$active_time_ranges[$i][0] = strtotime($current_date .' '.$j.':'.$spec_min_from.':00')+ 6*60*60;
			$active_time_ranges[$i][1] = strtotime($current_date .' '.$j.':'.$spec_min_to.':00')+ 6*60*60;
		}
	}
	elseif($current_day == $last_spec_day){
		$start_hour = 0;
		$end_hour = $last_spec_show_hour+1;
		$spec_day_type = 'Last day';
		for ($i=0; $i<$last_spec_show_hour+1; $i++){
				if ($i<10) $j = "0".$i;
				else $j=$i;
				
				$active_time_ranges[$i][0] = strtotime($current_date .' '.$j.':'.$spec_min_from.':00')+ 6*60*60;
				$active_time_ranges[$i][1] = strtotime($current_date .' '.$j.':'.$spec_min_to.':00')+ 6*60*60;
		}
	}
	else{
		$start_hour = 0;
		$end_hour = 24;
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
if ($next_start != '') $next_start = $next_start -20;
if ($next_stop != '') $next_stop = $next_stop +3;

    $hour =  intval( date("H", $server_time));      
    $opened = 0;
 
//configure here 
//for your requirements    
    //if($hour >= 0)
    //	$opened = 1;

//check opening and closing time..

//----------------------------------------------------------------------------------------
//$start_hour = 17;
//$end_hour = 21;
//---------------------------------------------------------------------------------------
	if($start_hour < $end_hour)
	{
		if( $hour >= $start_hour && $hour < $end_hour){
			$opened = 1;
		}
	}
	else {		
		if( $hour >= $start_hour && $hour <= $end_hour){
			$opened = 1;
		}
		else {
			if( $hour < $end_hour)	{
				$opened = 1;
			}			
		}
	}
	
	// if you want the controls always active, put the following below this line: $opened = 1;  //
$opened = 1; 

if($next_start == '') {
	if($next_stop == '') {
		$tomorrow = 1;
		$countdown = 1;
	}
	else{	}
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
	

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
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
<script language="javascript" type="text/javascript">
//##########################################
	var bt_enabled = 1;
	var bt_countdown = 0;
	var start_hour = <?php echo $start_hour;?>;
	var end_hour = <?php echo $end_hour;?>;
function func_load()
	{
	/*
		console.log('Button iframe reloaded');
		console.log('Open hour: <?php echo $start_hour;?>');
		console.log('Close hour: <?php echo $end_hour;?>');
		*/
<?php
		if($opened == 0)
		{
?>
			bt_enabled = 0;
			$('#green_on').removeAttr('href');
			$('#green_off').removeAttr('href');
			$('#white_on').removeAttr('href');
			$('#white_off').removeAttr('href');
			$('#blue_on').removeAttr('href');
			$('#blue_off').removeAttr('href');
			$('#red_on').removeAttr('href');
			$('#red_off').removeAttr('href');
			$('#star_on').removeAttr('href');
			$('#star_off').removeAttr('href');	
			$('#candy_on').removeAttr('href');
			$('#candy_off').removeAttr('href');	
			$('#snow_on').removeAttr('href');
			$('#snow_off').removeAttr('href');	
			$('#soldier_on').removeAttr('href');
			$('#soldier_off').removeAttr('href');		
	
			$("#div_status").html("Offline-Check back at 6pm CST!");
			$("#div_status1").html("Offline-Check back at 6pm CST!");

			$('#display').removeAttr('href');	
			$('#display1').removeAttr('href');	
			
<?php
		} 
?>	
	last_stop = parseInt(<?php echo $tomorrow; ?>);
		is_countdown_active = <?php echo $countdown; ?>;
		is_running = '<?php echo $output; ?>';
		spec_day = <?php echo $spec_day; ?>;
		next_start = <?php echo ($next_start - $current_timestamp-$modifier*60*60)*1000; ?>;
		if(is_countdown_active && is_running == '' && next_start > 0){
		
			if(last_stop == '0'){
				deadline = <?php echo ($next_start-$current_timestamp-$modifier*60*60)*1000; ?>;
				
				console.log('Buttons disable in: '+deadline/1000);
				if(deadline>0){
					setInterval(function(){
							disable_bt1();
							send_command_off_all(1,1);
							bt_countdown = 0;
							$("#div_status").html('Paused until the end of the show');
							console.log('buttons disabled 1st if');
							/*
							setInterval(function(){
								location.reload();
								
							}, 500);
							*/
					}, deadline);
				}
				else location.reload();
			}
			else location.reload();
		}
		else	{
			if(last_stop == '0'){
				disable_bt1();
				$("#div_status").html('Paused until the end of the show');
				console.log('buttons disabled 2nd if');
				
				<?php $stop = $next_stop - $current_timestamp-$modifier*60*60; ?>
				next_stop_timestamp = parseInt(<?php echo $stop; ?>);
				next_stop = new Date(next_stop_timestamp*1000);
				
				now_timestamp = new Date();
				deadline = next_stop_timestamp*1000;

				console.log('Buttons enable in: '+ next_stop_timestamp);
				setInterval(function(){
					enable_bt();
					$("#div_status").html('Ready');
					console.log('buttons enabled');
					location.reload();
				}, deadline);
			}
			else {
				<?php 
				$stop = ($last_stop - $current_timestamp-$modifier*60*60)+3; 
				
				//-------------------------------------------- Calculates the shut down time
				$end_hour = $end_hour.':00:00';
				$end_hour = strtotime($end_hour); //GMT turn off date
				$end_hour = $end_hour -$current_timestamp;
				
				//---------------------------------------------
				
				?>
				turn_off_controls = parseInt(<?php echo $end_hour ; ?>);
				last_stop_timestamp = parseInt(<?php echo $stop ; ?>);
				//console.log(last_stop_timestamp);
				//console.log('<?php echo $end_hour; ?>');
				
				
				if(last_stop_timestamp>0){
					
						disable_bt1();
						bt_countdown = 0;
						$("#div_status").html('Paused until the end of the show');
						console.log('buttons disabled for last show');
					
					now_timestamp = new Date();
					deadline = last_stop_timestamp*1000;
					

					console.log('Buttons enable after last show: '+ last_stop_timestamp);
					setInterval(function(){
						enable_bt();
						$("#div_status").html('Ready');
						console.log('buttons enabled');
						location.reload();
					}, deadline);
					
				}
				else{
					if (turn_off_controls>0){
						
						deadline = turn_off_controls*1000;

						console.log("Last show over. buttons disable in: "+ turn_off_controls);
						setInterval(function(){
							location.reload();
						}, deadline);
					}
				
					console.log('after last show');
				}
			}
		}


		//run_check_hour_timer();
			
	}	
	
	function run_check_hour_timer()
	{
		var t=setTimeout("check_hours()",1000);
	}
	function check_hours()
	{
        var ms = new Date().getTime().toString();
    	var seed = "&seed="+ms ;
		 
    	$.ajax({
    		type: "GET",
    		url: "cmd_ajax.php",
    		data: "cmd=status"+seed,
    		success: function(msg){
    			 //alert(msg );
				 var t = msg.split("#");
				 //alert(t.length);
				 if( t.length > 0)
				 {
	    			 if(t[0] == "1")
	                 {
	                   //$("#div_status").html("Sent.");
	                   //disable_bt();
	                 }
	                 else
	                 {              
					 	//disable_bt();
	                   	$("#div_status").html(t[1]);                   
	                 }
				 }
				 run_check_hour_timer();
    		}
    	});
		
	}
	function disable_bt()
	{		
		bt_enabled = 0;
		bt_countdown = 6;
		//var t=setTimeout("enable_bt()",5000);
		var t=setTimeout("enable_bt()",1000);
		$('#green_on').removeAttr('href');
		$('#green_off').removeAttr('href');
		$('#white_on').removeAttr('href');
		$('#white_off').removeAttr('href');
		$('#blue_on').removeAttr('href');
		$('#blue_off').removeAttr('href');
		$('#red_on').removeAttr('href');
		$('#red_off').removeAttr('href');
		$('#star_on').removeAttr('href');
		$('#star_off').removeAttr('href');	
		$('#candy_on').removeAttr('href');
		$('#candy_off').removeAttr('href');
		$('#snow_on').removeAttr('href');
		$('#snow_off').removeAttr('href');	
		$('#soldier_on').removeAttr('href');
		$('#soldier_off').removeAttr('href');		
	}
	function disable_bt1()
	{		
		bt_enabled = 0;
		bt_countdown = 0;
		//var t=setTimeout("enable_bt()",5000);
		$('#green_on').removeAttr('href');
		$('#green_off').removeAttr('href');
		$('#white_on').removeAttr('href');
		$('#white_off').removeAttr('href');
		$('#blue_on').removeAttr('href');
		$('#blue_off').removeAttr('href');
		$('#red_on').removeAttr('href');
		$('#red_off').removeAttr('href');
		$('#star_on').removeAttr('href');
		$('#star_off').removeAttr('href');	
     	        $('#candy_on').removeAttr('href');
		$('#candy_off').removeAttr('href');	
		$('#snow_on').removeAttr('href');
		$('#snow_off').removeAttr('href');
		$('#soldier_on').removeAttr('href');
		$('#soldier_off').removeAttr('href');	
	}	
	function enable_bt()
	{
		if(bt_countdown > 0)
		{
			//if(deadline>0 && deadline<11){location.reload();}
				bt_countdown = bt_countdown - 1;
				var d = $("#div_status").html();
				if(bt_countdown ==0)
				{
					$("#div_status").html( d + ".OK.");
								location.reload();
				}
				else{
					$("#div_status").html( d + "."+bt_countdown);
				}
				var t=setTimeout("enable_bt()",1000);
				return 1;
			
		}
		clearTimeout();
		bt_enabled = 1;
		$("#div_status").html("Ready");
		$("#green_on").attr("href", "#");
		$("#green_off").attr("href", "#");
		$("#white_on").attr("href", "#");
		$("#white_off").attr("href", "#");
		$("#blue_on").attr("href", "#");
		$("#blue_off").attr("href", "#");
		$("#red_on").attr("href", "#");
		$("#red_off").attr("href", "#");
		$("#star_on").attr("href", "#");
		$("#star_off").attr("href", "#");
		$('#candy_on').attr("href", "#");
		$('#candy_off').attr("href", "#");
		$('#snow_on').attr("href", "#");
		$('#snow_off').attr("href", "#");
		$('#soldier_on').removeAttr('href');
		$('#soldier_off').removeAttr('href');	
		
	}
		
    function send_command(device_id,device_state)
    {
        if(bt_enabled == 0) 
        {
            //alert("Try after some time...");
            return;
        }
    	disable_bt1();
        //alert("updating :: "+ m_gv_tbl_id);
        var ms = new Date().getTime().toString();
    	var seed = "&seed="+ms ;

        $("#div_status").html("Sending...");

    	var cmd_name = "send";
        //  /cmd_ajax.php?cmd=send&device_id=A1&device_state=1
    	$.ajax({
    		type: "GET",
    		url: "cmd_ajax.php",
    		data: "cmd="+cmd_name+"&device_id="+device_id+"&device_state="+device_state+seed,
    		success: function(msg){
    			 //alert(msg );
    			 if(msg.indexOf("CMD-OK") > -1)
                 {
                   $("#div_status").html("Sent.");
                   disable_bt();
                 }
                 else
                 {
                   $("#div_status").html("Failed!");
                   enable_bt();
                 }
    		}
    	});
    }
	function send_command_off_all(device_id,device_state){
       
        //alert("updating :: "+ m_gv_tbl_id);
        var ms = new Date().getTime().toString();
    	var seed = "&seed="+ms ;

    	var cmd_name = "disable_all";
        //  /cmd_ajax.php?cmd=send&device_id=A1&device_state=1
    	$.ajax({
    		type: "GET",
    		url: "cmd_ajax.php",
    		data: "cmd="+cmd_name+"&device_id="+device_id+"&device_state="+device_state+seed,
    		success: function(msg){
    			 //alert(msg );
    			 if(msg.indexOf("CMD-OK") > -1) {console.log('All lights disabled'); location.reload(); }
                 else  {console.log('"Disable all" command failed'); location.reload(); }
    		}
    	});
		
		//location.reload();
    }
	function clear_display()
	{
		$('textarea#id_display').val("");
	}
    function send_display()
    {
        if(bt_enabled == 0) 
        {
            return;
        }
        //alert("updating :: "+ m_gv_tbl_id);
        var ms = new Date().getTime().toString();
    	var seed = "&seed="+ms ;

        $("#div_status1").html("Sending...");

		//alert($('#id_display').val());
		
    	var cmd_name = "display";
    	var cmd_value = $('#id_display').val().replace('\n','<br />');
    	cmd_value = cmd_value.replace(/\n/g,'<br />');
    	//alert(cmd_value);
    	
    	if( cmd_value.length == 0)
    	{
    		$("#div_status1").html("Error: Please Enter your message");
    		return;
        }
        //  /cmd_ajax.php?cmd=send&device_id=A1&device_state=1
    	$.ajax({
    		type: "GET",
    		url: "cmd_ajax.php",
    		data: "cmd="+cmd_name+"&display_text="+cmd_value+seed,
    		success: function(msg){
    			 //alert(msg );
    			 if(msg.indexOf("CMD-OK") > -1)
                 {
                   $("#div_status1").html("Sent.");
                   
                 }
                 else
                 {
                   $("#div_status1").html("Failed!");
                 }
    		}
    	});
    }
</script>
<style type="text/css">
.button_ct{
	width: 122px;
	text-align: center;
	margin: 3px 4px 4px 5px;
	padding: 5px 2px;
	display: inline-block;
}

.button_ct b{
	font-size: 13px;
}

</style>

</head>

<body onLoad="func_load();">




	<div id="wrapper">

<?php
	$php_name = "manager";
?>


		<div id="content_conf">


                    <div id="div_status" name="div_status" align="center">
				     	Ready
				    </div>
						<div style="margin-left:4px;">
				                <div class="green_table button_ct" align="center">
								    <table>
									<tr>
									<td>
				                        <a href="#" id="green_on" class="button" onClick="send_command('A2',1);return(false);" >On</a>
				                    </td>
									<td>
										<b>Green</b>
									</td>
									<td>
										<a href="#" id="green_off" class="button" onClick="send_command('A2',0);return(false);" >Off</a>
									</td>
									</tr>
				    				</table>
				    		    </div>

				      			<div class="white_table button_ct">
				      				<table>
				      					<tr>
				      						<td>
				      							<a href="#" id="white_on" class="button" onClick="send_command('A3',1);return(false);" >On</a>
				      						</td>
				      						<td>
				      							<b>White</b>
				      						</td>
				      						<td>
				      							<a href="#" id="white_off" class="button" onClick="send_command('A3',0);return(false);" >Off</a>
				      						</td>
				      					</tr>
				      				</table>
				      			</div>
                

				              	<div class="red_table button_ct" align="center">
				      				<table>
				      					<tr>
				      						<td>
				      							<a href="#" id="red_on" class="button" onClick="send_command('A4',1);return(false);" >On</a>
				      						</td>
				      						<td>
				      							<b>Red</b>
				      						</td>
				      						<td>
				      							<a href="#" id="red_off" class="button" onClick="send_command('A4',0);return(false);" >Off</a>
				      						</td>
				      					</tr>
				      				</table>
				      			</div>
								
								<div class="blue_table button_ct" valign="top" align="center">
				      				<table>
				      					<tr>
				      						<td>
				      							<a href="#" id="blue_on" class="button" onClick="send_command('A5',1);return(false);" value="On">On</a>
				      						</td>
				      						<td>
				      							<b>Blue</b>
				      						</td>
				      						<td>
				      							<a href="#" id="blue_off" class="button" onClick="send_command('A5',0);return(false);" value="Off">Off</a>
				      						</td>
				      					</tr>
				      				</table>
				      			</div>
								
								<div class="star_table button_ct" align="center">
				      				<table>
				      					<tr>
				      						<td>
				      							<a href="#" id="star_on" class="button" onClick="send_command('A6',1);return(false);" value="On">On</a>
				      						</td>
				      						<td>
				      							<b>Star</b>
				      						</td>
				      						<td>
				      							<a href="#" id="star_off" class="button" onClick="send_command('A6',0);return(false);" value="Off">Off</a>
				      						</td>
				      					</tr>
				      				</table>
				      			</div>                
                
				              	<div class="snow_table button_ct" align="center">
				      				<table>
				      					<tr>
				      						<td>
				      							<a href="#" id="snow_on" class="button" onClick="send_command('A8',1);return(false);" >On</a>
				      						</td>
				      						<td>
				      							<b>Snow</b>
				      						</td>
				      						<td>
				      							<a href="#" id="snow_off" class="button" onClick="send_command('A8',0);return(false);" >Off</a>
				      						</td>
				      					</tr>
				      				</table>
				      			</div>

				      			<div class="candy_table button_ct" valign="top" align="center">
				      				<table>
				      					<tr>
				      						<td>
				      							<a href="#" id="candy_on" class="button" onClick="send_command('A7',1);return(false);" value="On">On</a>
				      						</td>
				      						<td>
				      							<b>Candy</b>
				      						</td>
				      						<td>
				      							<a href="#" id="candy_off" class="button" onClick="send_command('A7',0);return(false);" value="Off">Off</a>
				      						</td>
				      					</tr>
				      				</table>
				      			</div>
				              	<div class="soldier_table button_ct" align="center">
				      				<table>
				      					<tr>
				      						<td>
				      							<a href="#" id="soldier_on" class="button" onClick="send_command('A9',1);return(false);" >On</a>
				      						</td>
				      						<td>
				      							<div id="soldier-space"></div>
				      						</td>
				      						<td>
				      							<a href="#" id="soldier_off" class="button" onClick="send_command('A9',0);return(false);" >Off</a>
				      						</td>
				      					</tr>
				      				</table>
				      			</div>
							</div>
   
                    <br /><br /><br /><br /><br /><br /><br />
                    <!--
                    <div id="div_status" name="div_status" align="center">
				     	Ready
				    </div>
				    <br />
				    
				    <div id="button_holder">
						<table class="button_holder_table" align="center" border="0">
							<tr >
				            <td width="100%" valign="top" align="center">
				                <div class="green_table" align="center">
								    <table>
									<tr>
									<td>
				                        <a href="#" id="green_on" class="button" onClick="send_command('A1',1);return(false);" >On</a>
				                    </td>
									<td>
										<b>A1</b>
									</td>
									<td>
										<a href="#" id="green_off" class="button" onClick="send_command('A1',0);return(false);" >Off</a>
									</td>
									</tr>
				    				</table>
				    		    </div>
				      			<div class="white_table">
				      				<table>
				      					<tr>
				      						<td>
				      							<a href="#" id="white_on" class="button" onClick="send_command('A2',1);return(false);" >On</a>
				      						</td>
				      						<td>
				      							<b>A2</b>
				      						</td>
				      						<td>
				      							<a href="#" id="white_off" class="button" onClick="send_command('A2',0);return(false);" >Off</a>
				      						</td>
				      					</tr>
				      				</table>
				      			</div>
				              	<div class="red_table">
				      				<table>
				      					<tr>
				      						<td>
				      							<a href="#" id="red_on" class="button" onClick="send_command('A3',1);return(false);" >On</a>
				      						</td>
				      						<td>
				      							<b>A3</b>
				      						</td>
				      						<td>
				      							<a href="#" id="red_off" class="button" onClick="send_command('A3',0);return(false);" >Off</a>
				      						</td>
				      					</tr>
				      				</table>
				      			</div>
				                <br />
				      			<div class="blue_table" valign="top">
				      				<table>
				      					<tr>
				      						<td>
				      							<a href="#" id="blue_on" class="button" onClick="send_command('A4',1);return(false);" value="On">On</a>
				      						</td>
				      						<td>
				      							<b>A4</b>
				      						</td>
				      						<td>
				      							<a href="#" id="blue_off" class="button" onClick="send_command('A4',0);return(false);" value="Off">Off</a>
				      						</td>
				      					</tr>
				      				</table>
				      			</div>
				                <br />
				      			<div class="star_table">
				      				<table>
				      					<tr>
				      						<td>
				      							<a href="#" id="star_on" class="button" onClick="send_command('A5',1);return(false);" value="On">On</a>
				      						</td>
				      						<td>
				      							<b>A5</b>
				      						</td>
				      						<td>
				      							<a href="#" id="star_off" class="button" onClick="send_command('A5',0);return(false);" value="Off">Off</a>
				      						</td>
				      					</tr>
				      				</table>
				      			</div>
				                <br />
                                                         <div class="candy_table">
								    <table>
									<tr>
									         <td>
				                                                         <a href="#" id="candy_on" class="button" onClick="send_command('A6',1);return(false);" >On</a>
				                    </td>
									<td>
										<b>A6</b>
									</td>
									<td>
										          <a href="#" id="candy_off" class="button" onClick="send_command('A6',0);return(false);" >Off</a>
									</td>
									</tr>
				    				</table>
				    		    </div>
				       		</td>
				            </tr>
				        </table>
					</div>
				
				    <br /><br /><br /><br /><br />
				    -->
		    	</td>
		    	<td style="width: 50%;vertical-align: top;" >
	      				
		    	</td>
		    </tr>
		    </table>

		</div> <!-- end #content -->


<?php include('includes/footer.php'); ?>
    </div> 	<!-- End #wrapper -->
	
	</body>

</html>
