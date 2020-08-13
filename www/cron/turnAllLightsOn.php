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

$query = "insert into `tbl_commands`(device_id, device_state, entry_date) values ('B1',1, now()), ('B2',1, now()), ('B3',1, now()), ('B4',1, now()), ('B5',1, now()), ('B6',1, now()), ('B7',1, now()), ('B8',1, now()), ('B9',1, now()), ('B10',1, now()), ('B11',1, now()), ('B12',1, now()), ('B13',1, now()), ('B14',1, now()), ('B15',1, now()), ('B16',1, now())";
mysqli_query($link, $query)or die(mysqli_error($link));

mysqli_close($link);