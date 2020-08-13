<?php
	// cmd_ajax.php?cmd=send&device_id=A1&device_state=1
	session_start();
	include_once '../variables/variables.php';
	require_once '../db.php';

//    require_once 'lib/class.eyemysqladap.inc.php';
	// Load the database adapter
	//$db = new EyeMySQLAdap($server_name, $user_name, $user_pass, $db_name);

    //$sql = "select message,created_date from tbl_display where status=1 order by created_date desc limit 1";
    $sql = "select message, ( case when ( now() < DATE_ADD(created_date,INTERVAL 2 MINUTE) ) then 1 else 0 end) as display_now from tbl_display where status=1 order by created_date desc limit 1";
    
    $res = mysqli_query($dbCon, $sql);
    if (empty($res)) {
        echo "";
        exit();
    }
	if ($row = $res->fetch_assoc()) {
        $display_msg = urldecode($row['message']);
        $display_now = $row['display_now'];
        //print_r( $display_dt ) ;
        //echo urldecode( $display_msg);
	    $sql = "select * from tbl_filter order by id";
	    $res = mysqli_query($dbCon, $sql);
	    if (empty($res)) {
	        $display_msg =  $display_msg;
	    }
	    else
	    {
			while ($row = $res->fetch_assoc()) {
		        $pat_str =  $row['pattern_str'];
		        $repl_str =  $row['replace_str'];
		        //echo $pat_str." : ".$repl_str;
		        $display_msg = preg_replace($pat_str, $repl_str, $display_msg);
		    }
	    }  
        //echo $display_msg;
        echo $display_now.$display_msg;    
        exit();
    }
   
?>