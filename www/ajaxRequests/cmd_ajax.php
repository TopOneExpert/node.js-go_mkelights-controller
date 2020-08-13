<?php
	// cmd_ajax.php?cmd=send&device_id=A1&device_state=1
	session_start();
	include_once '../variables/variables.php';
	require_once '../db.php';
	include '../config.php';
//    require_once '../lib/class.eyemysqladap.inc.php';
	// Load the database adapter
//	$db = new EyeMySQLAdap($server_name, $user_name, $user_pass, $db_name);
	$db = new mysqli($server_name, $user_name, $user_pass, $db_name);

    $cmd = $_GET["cmd"];

	$device_id = "";
	if(isset($_GET["device_id"])) $device_id = $_GET["device_id"];
	$device_state = 0;
	if(isset($_GET["device_state"])) $device_state = $_GET["device_state"];
	$display_text = "";
	if(isset($_GET["display_text"])) $display_text = $_GET["display_text"];
	
	if($cmd == "status")
	{
		$hour =  intval( date("H"));
		$minute =  intval( date("i"));
		
		//echo date();
		$opened = 0;
		//Remove the /* commented */
		/*
		if($start_hour < $end_hour)
		{
			if( $hour >= $start_hour && $hour < $end_hour)
			{
				$opened = 1;
			}
		}
		else 
		{		
			if( $hour >= $start_hour && $hour <= 23)
			{
				$opened = 1;
			}
			else 
			{
				if( $hour < $end_hour)
				{
					$opened = 1;
				}			
			}
		}*/
		$opened = 1;
		if($opened == 0)
		{
			$rem = $start_hour-$hour;
			$min = 0;
			if($minute >0)
			{
				$rem = $rem - 1;
				$min = 60-$minute;
			}
			echo "0#Check back in $rem hours $min minutes";
			exit();		
		}
		else
		{
			echo "1#";
			
			
			exit();
			
		}
		
	}
	if($cmd == "send")
	{
        $query = "insert into `tbl_commands`(device_id, device_state, entry_date) values ('".$device_id."',".$device_state.", now())";
        //echo $query;
        mysqli_query($dbCon, $query)or die(mysqli_error());
        echo "CMD-OK";
    }
	elseif($cmd == "display")
	{
		$ipaddress = $_SERVER["REMOTE_ADDR"];
		
		$ip_hit = find_ipaddress_count($ipaddress);
		
		if( $ip_hit < $ip_address_block_filter_limit )
		{
			if( find_message_in_filter($display_text) > 0 ) //in filter
			{				
				$query = "insert into tbl_ipaddress(ip_address,blocked,created_date) values('$ipaddress',0,now()) ";
				mysqli_query($dbCon, $query)or die(mysqli_error());
				$query = "insert into `tbl_display` (`message`, `created_date`, `status`,`ip_address`) values('".$msg."',now(),1,'$ipaddress')";
				//echo $query;
				mysqli_query($dbCon, $query)or die(mysqli_error());
			}
			$msg = urlencode($display_text);
	        $query = "insert into `tbl_display` (`message`, `created_date`, `status`,`ip_address`) values('".$msg."',now(),1,'$ipaddress')";
	        //echo $query;
	        mysqli_query($dbCon, $query)or die(mysqli_error());
	        echo "CMD-OK";
		}
		else
		{
	        echo "CMD-KO#You Have Been Banned.";
		}				
    }
	elseif($cmd == "disable_all")
	{
		$query = "insert into `tbl_commands`(device_id, device_state, entry_date) values ('A2',0, now()), ('A3',0, now()), ('A4',0, now()), ('A5',0, now()), ('A6',0, now()), ('A7',0, now()), ('A8',0, now()), ('A9',0, now())";
        mysqli_query($dbCon, $query)or die(mysqli_error());
        echo "CMD-OK";
		
    }
    else
    {
        echo "CMD-KO";
	}
	function find_message_in_filter($display_text) 
	{
		global $dbCon;
		$cnt = 0;
		$sql = "select * from tbl_filter order by id";
		$res = mysqli_query($dbCon, $sql);
		if (empty($res)) {
			return 0;
		}
		else
		{
			while ($row = $res->fetch_assoc()) {
				$pat_str =  $row['pattern_str'];
				$repl_str =  $row['replace_str'];
				$cnt1 = 0;
				$display_text = preg_replace($pat_str, $repl_str, $display_text,-1,$cnt1);
				if($cnt1>0)
					$cnt += $cnt1;
			}
		}
		return $cnt;		
	}
	function find_ipaddress_count($ipaddress)
	{
		global $dbCon;
		$sql = "select count(*) as cnt from tbl_ipaddress where ip_address='$ipaddress'";
		$res = mysqli_query($dbCon, $sql);
		if (empty($res)) {
			return 0;
		}
		else
		{
			if ($row = $res->fetch_assoc()) {
				return  $row['cnt'];
			}
		}
		return 0;			
	}
	function is_blocked($ipaddress)
	{
		$sql = "select * from tbl_ipaddress where ip_address='$ipaddress' and blocked=1";
		$res = mysqli_query($dbCon, $sql);
		if (empty($res)) {
			return 0;
		}
		else
		{
			return 1;
		}		
	}
	exit();
	
?>