<?php

$dbc = array(
	'hostname' => 'localhost',
	'username' => 'lights_slave',
	'password' => '8%PQgWoSD?dw',
	'database' => 'lights_master',
);

$link = mysqli_connect($dbc['hostname'],$dbc['username'],$dbc['password'],$dbc['database']);

if (mysqli_connect_errno())
{
	echo "Error connecting to database: " . mysqli_connect_error();
}

$query = "insert into `tbl_commands`(device_id, device_state, entry_date) values ('B9',1, now())";
mysqli_query($link, $query)or die(mysqli_error($link));

mysqli_close($link);