#!/usr/bin/php
<?php
/**
THESANTATRACKER.COM Tree System
*/

error_reporting(E_ALL | E_STRICT);

// Connect Database

$dbc = array(
    'hostname' => 'northpole.troublesomestudios.net',
    'username' => 'lights_slave',
    'password' => '8%PQgWoSD?dw',
    'database' => 'lights_master',
);

$link = mysqli_connect($dbc['hostname'],$dbc['username'],$dbc['password'],$dbc['database']);

if (mysqli_connect_errno())
{
  echo "Error connecting to database: " . mysqli_connect_error();
}

while(1)
{
    $res = fetch_command_queue();
    while ($row = $res->fetch_assoc()) {
        process_call_queue($row);
    }
    sleep(10);
}

function process_call_queue($row)
{

    $ID = $row['id'];
    $devID = $row['device_id'];
    $devVal = $row['device_state'];

    if($devVal == 1)
    {
        send_to_x10($devID,"fon");
    }
    else
    {
        send_to_x10($devID,"foff");
    }
    $sql = sprintf("UPDATE `tbl_commands` SET `status`=0  WHERE id = %d",$ID);
    global $link;
    mysqli_query($link, $sql);

}

function fetch_command_queue()
{
    $sql = "select id,device_id, device_state from `tbl_commands` where `status`=1 order by id asc limit 0, 10";
    global $link;
    //echo "sql:$sql\n";
    $res = mysqli_query($link, $sql);
    if (empty($res)) {
        syslog(LOG_INFO,$link->error . " Err\n");
        die($link->error . " Err\n");
    }
    return $res;
}

//
function send_to_x10($dev_id,$dev_state)
{
    $bin_heyu = "/usr/local/bin/heyu";
    $cmd = sprintf('%s %s "%s"',$bin_heyu,$dev_state,$dev_id);
    syslog(LOG_INFO,$cmd);
    exec($cmd,$out,$ret);
    syslog(LOG_INFO,"Return: $ret");
    return array('return'=>$ret,'stdout'=>implode("\n",$out));
}
