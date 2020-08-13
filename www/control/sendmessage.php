<?php
	session_start();

    header("Expires: Tue, 28 Aug 2007 12:34:56 GMT ");

    require_once 'db.php';
	
    date_default_timezone_set('CST6CDT');
    
    $hour =  intval( date("H"));
    $opened = 0;

//configure here 
//for your requirements    
    if($hour >= 18 && $hour <= 22)
    	$opened = 1;
    
    $opened = 1;
    
    //echo $hour;
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Online - Christmas Tree</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="author" content="" />
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
<script src="js/jquery-1.2.6.min.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
//##########################################
	var bt_enabled = 1;
	var sd_enabled = 1;
	var sd_value = 15;
	var sd_timer;
	function func_load()
	{
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

			$("#div_status").html("Offline-Check back at 6pm CST!");
			$("#div_status1").html("Offline-Check back at 6pm CST!");

			$('#display').removeAttr('href');	
			$('#display1').removeAttr('href');	
			
<?php
		} 
?>	
	}
	function disable_bt()
	{		
		bt_enabled = 0;
		var t=setTimeout("enable_bt()",5000);
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
		
	}
	function enable_bt()
	{
		clearTimeout();
		bt_enabled = 1;

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
		
	}
	function enable_sd()
	{
		sd_enabled = 1;
		clearTimeout(sd_timer);
		$("#display").attr("href", "#");
		$("#div_status1").html("Ready");
	}
	function update_sd()
	{
		clearTimeout(sd_timer);
		if(sd_value > 0 )
		{
			sd_value = sd_value - 1;
			$("#div_status1").html("Sent!...wait for "+ sd_value +" seconds..");
			sd_timer=setTimeout("update_sd()",1000);
		}
		else
		{
			enable_sd();
		}
	}
	function disable_sd()
	{
		sd_enabled = 0;
		sd_value = 15;
		$("#div_status1").html("Sent!...wait for "+ sd_value +" seconds..");
		sd_timer=setTimeout("update_sd()",1000);
		$('#display').removeAttr('href');	
	}	
    function send_command(device_id,device_state)
    {
        if(bt_enabled == 0) 
        {
            //alert("Try after some time...");
            return;
        }
    	disable_bt();
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
                 }
                 else
                 {
                   $("#div_status").html("Failed!");
                 }
    		}
    	});
    }
	function clear_display()
	{
		$('textarea#id_display').val("");
	}
    function send_display()
    {
        if(sd_enabled == 0) 
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
                   disable_sd();
                 }
                 else
                 {
                     //alert(msg);
                	 msg = msg.substring(7);
                	 if(msg.length > 1)
                		 $("#div_status1").html(msg);
                	 else    
                     	$("#div_status1").html("Failed!");
                 }
    		}
    	});
    }

</script>

</head>

<body onLoad="func_load();">
		    	<td style="width: 50%;vertical-align: top;" >
				    <br />
	      			<div >
	      				<table>
	      					<tr>
	      						<td>
	      							<textarea rows="2" cols="40" maxlength="30" name="id_display" id="id_display"></textarea>
	      						</td>
	      					</tr>
	      					<tr>
	      						<td>
	      							<br/>
	      						</td>
	      					</tr>
	      					<tr>
	      						<td>
	      							<table>
	      								<tr>
	      									<td><a href="#" id="display" class="button" onClick="send_display();return(false);" value="Send">&nbsp;&nbsp;&nbsp;&nbsp;Send&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
	      									<td><div id="div_status1" name="div_status1" align="center" style="padding-left: 20px;">Ready</div></td>
	      								</tr>
	      								<tr>
	      									<td><br/><a href="#" id="display1" class="button" onClick="clear_display();return(false);" value="Send">Clear</a></td>
	      									<td></td>
	      								</tr>
	      							</table>
	      						</td>
	      					</tr>

	      				</table>
	      				
		    	</td>
		    </tr>
		    </table>

		</div> <!-- end #content -->


	</body>

</html>
