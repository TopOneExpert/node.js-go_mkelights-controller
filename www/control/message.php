<?php
	session_start();

    header("Expires: Tue, 28 Aug 2007 12:34:56 GMT ");

    require_once 'db.php';
	
    date_default_timezone_set('CST6CDT');
    
    $hour =  intval( date("H"));
    $opened = 0;

//configure here 
//for your requirements    
    if($hour <= 22 && $hour >= 18)
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

</head>
&nbsp
	      			<div>
	      					<tr>
	      						<td>
	      							<textarea style="width: 285px; margin: 0 auto; height: 18px; margin-left: 10px;" maxlength="25" rows="1" cols="15" name="id_display" id="id_display"></textarea>
	      						</td>
	      					</tr>
	      					<tr>
	      						<td>
	      								<tr>
	      									<td><a href="#" id="display" class="button" onClick="send_display();return(false);" value="Send">&nbsp;&nbsp;&nbsp;&nbsp;Send&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
	      								
	      								</tr>
	      						</td>
	      					</tr>

		</div> <!-- end #content -->

    </div> 	<!-- End #wrapper -->

	</body>

</html>
