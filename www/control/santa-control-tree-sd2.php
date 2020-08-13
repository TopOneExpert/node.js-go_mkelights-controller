<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
var bt_enabled=1;
var sd_enabled = 1;
var sd_value = 15;
var sd_timer;
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
    		url: "/srv_backup/cmd_ajax.php",
    		//dataType: "jsonp",
    		//crossDomain: true,
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
                   //$("#div_status1").html("Failed!");
                	 msg = msg.substring(7);
                	 if(msg.length > 1)
                		 $("#div_status1").html(msg);
                	 else    
                     	$("#div_status1").html("Failed!");                     
                 }
    		}
    	});
    }
function clear_display(){
	$("#id_display").val("");
}
</script>
<style type="text/css">
@font-face {
	font-family: "MaidenOrange";
	font-style: normal;
	src: local("MaidenOrange"), url("fonts/MaidenOrange.ttf") format("truetype"); /* non-IE */
	
}
	div.buttonSend
	{
		background-image:url('images/sendButton.png');
		width: 80px;
		height: 25px;
		display: inline-block;
        margin-top: 5px;
		cursor: pointer;
	}
	div.buttonClear
	{
		background-image:url('images/clearButton.png');
		width: 52px;
		height: 25px;
		display: inline-block;
        margin-top: 5px;
		cursor: pointer;
	}
	#share_text_header{
		color: white;
		font-family: MaidenOrange;
		font-size: 24px;
		margin-bottom: 4px;
		text-align: center;
		text-shadow: 3px 3px 4px black;
	}
</style>
</head>
<body>
<div style="height: 115px; margin-bottom: 8px; margin-top:-4px; background: #500403">                
                					<center id="share_text_header">Share A Christmas Message</center>
      							<textarea style="width: 280px; margin: 0 auto; height: 18px; margin-left: 3px; margin-right: 3px;" maxlength="30" rows="1" cols="15" name="id_display" id="id_display"></textarea>
  								<br/>
  								&nbsp;	  								
  								&nbsp;
  								&nbsp;
								<div style="display:inline-block; width:100%; text-align:center; margin-top:-18px">
									<div id="display" class="buttonSend" onClick="send_display();return(false);" value="Send">&nbsp;</div>
									<div id="display1" class="buttonClear" onClick="clear_display();return(false);" value="Send">&nbsp;</div>
									<div id="div_status1" name="div_status1" align="center" style="color:#fff"></div>	
								</div>
      		</div>
</body>
</html>    		
	      		