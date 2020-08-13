<?php

	$db_type = 1;

	if($db_type == 0){//local-laptop
		$server_name = "localhost";
		$user_name = "lights_slave";
		$user_pass = "8%PQgWoSD?dw";
		$db_name = "lights_master";
	}
	else if($db_type == 1)//MKEServer
	{
//		$server_name = "mkelights.com";
//		$user_name = "lights_slave";
//		$user_pass = "8%PQgWoSD?dw";
//		$db_name = "lights_master";

		$server_name = "localhost";
		$user_name = "lights_slave";
		$user_pass = "8%PQgWoSD?dw";
		$db_name = "lights_master";
		
	}

	$dbCon = mysqli_connect($server_name, $user_name, $user_pass, $db_name);
	
?>