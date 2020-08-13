<?php
	session_start();

    header("Expires: Tue, 28 Aug 2007 12:34:56 GMT ");

    require_once 'db.php';
    
	include 'config.php';
    
    $hour =  intval( date("H"));      
	    
    $opened = 0;
 
//configure here 
//for your requirements    
    //if($hour >= 0)
    //	$opened = 1;

//check opening and closing time..
	if($start_hour < $end_hour)
	{
		if( $hour >= $start_hour && $hour < $end_hour)
		{
			$opened = 1;
		}
	}
	else 
	{		
		if( $hour >= $start_hour && $hour <= $end_hour)
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
	}
	
	// if you want the controls always active, put the following below this line: $opened = 1;  //

	
    	
    //echo " $hour : $start_hour : $end_hour : $opened";
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
	var bt_countdown = 0;
	var start_hour = <?php echo $start_hour;?>;
	var end_hour = <?php echo $end_hour;?>;
	
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
			$('#candy_on').removeAttr('href');
			$('#candy_off').removeAttr('href');		
	
			$("#div_status").html("Offline-Check back at 6pm CST!");
			$("#div_status1").html("Offline-Check back at 6pm CST!");

			$('#display').removeAttr('href');	
			$('#display1').removeAttr('href');	
			
<?php
		} 
?>	
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
	}	
	function enable_bt()
	{
		if(bt_countdown > 0)
		{
			bt_countdown = bt_countdown - 1;
			var d = $("#div_status").html();
			if(bt_countdown ==0)
			{
				$("#div_status").html( d + ".OK.");
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
		$('#candy_on').removeAttr('href');
		$('#candy_off').removeAttr('href');	
		
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

<body onLoad="func_load();">




	<div id="wrapper">

<?php
	$php_name = "manager";
?>


		<div id="content_conf">


		    <table width="90%" border="0" >
		    <tr>
		    	<td style="width: 50%">

		    <table width="90%" border="0" >
		    <tr>
		    	<td colspan="3">
                    <div id="div_status" name="div_status" align="center">
				     	Ready
				    </div>
                </td>
            </tr>
		    <tr>
		    	<td>
				                <div class="green_table" align="center">
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
                
                </td>
                <td>
				      			<div class="white_table">
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
                
                
                </td>
                <td>
				              	<div class="red_table">
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
                
                
                
                </td>

            </tr>
		    <tr>
		    	<td>
				      			<div class="blue_table" valign="top">
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
                
                </td>
                <td>
				      			<div class="star_table">
				      				<table>
				      					<tr>
				      						<td>
				      							<a href="#" id="star_on" class="button" onClick="send_command('A6',1);return(false);" value="On">On</a>
				      						</td>
				      						<td>
				      							<b>Snow</b>
				      						</td>
				      						<td>
				      							<a href="#" id="star_off" class="button" onClick="send_command('A6',0);return(false);" value="Off">Off</a>
				      						</td>
				      					</tr>
				      				</table>
				      			</div>
                
                <td>
				      			<div class="candy_table" valign="top">
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
                
                </td>

                </td>
                <td>&nbsp;</td>
            </tr>
            </table>        
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
