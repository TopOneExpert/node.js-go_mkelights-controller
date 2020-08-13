<?php
	session_start();

    header("Expires: Tue, 28 Aug 2007 12:34:56 GMT ");

    require_once 'db.php';
    $display_text = "";
    $display_now = 0;
    
    $sql = "select message, ( case when ( now() < DATE_ADD(created_date,INTERVAL 1 MINUTE) ) then 1 else 0 end) as display_now from tbl_display where status=1 order by created_date desc limit 1";
    $res = mysqli_query($dbCon, $sql);
    if (empty($res)) {
        $display_text =  "";
        $display_now = 0;
    }
	if ($row = $res->fetch_assoc()) {
        $display_text =  urldecode($row['message']);
        $display_now = $row['display_now'];        
    }
    
    $sql = "select * from tbl_filter order by id";
    $res = mysqli_query($dbCon, $sql);
    if (empty($res)) {
        $display_text =  $display_text;
    }
    else
    {
		while ($row = $res->fetch_assoc()) {
	        $pat_str =  $row['pattern_str'];
	        $repl_str =  $row['replace_str'];
	        $display_text = preg_replace($pat_str, $repl_str, $display_text);
	    }
    }    

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<title>Online - Christmas Tree</title>

<META HTTP-EQUIV="REFRESH" CONTENT="30">

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<meta name="description" content="" />

<meta name="keywords" content="" />

<meta name="author" content="" />

<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
<style type="text/css">
.Text {
	color: #FFF;
	font-size: 1500%;
	width:100%;
	height:100%;
	margin:0;
	padding:0;
	text-align: center;
        display:table-cell;
        vertical-align: middle;
        
}
body {
	background-color: #000;
	margin: 0;
	padding: 0;
	width: 100%;
	height: 100%;
}
</style>

<script src="js/jquery-1.2.6.min.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript">
//##########################################

	function func_load()
	{
		var t=setTimeout("get_display_text()",1000);
	}

    function get_display_text()
    {
        clearTimeout();
        
        //alert("updating :: "+ m_gv_tbl_id);
        var ms = new Date().getTime().toString();
    	var seed = "&seed="+ms ;

//        $("#div_status").html("Sending...");

    	var cmd_name = "send";
    	$.ajax({
    		type: "GET",
    		url: "ajax_display.php",
    		data: "cmd=get"+seed,
    		success: function(msg){
    			 //alert(msg );
    			 var display_now = msg.substring(0,1);
    			 msg = msg.substring(1);
    			 //alert(display_now + " :: " + msg);
    			 if(msg.length>5)
    			 {
    			 	$("#id_display").css({fontSize:"1300%"});
    			 }
    			 else
    			 {
    			 	$("#id_display").css({fontSize:"2000%"}); 
    			 }
    			 $("#id_display").html("");
    			 if(display_now == "1")
    			 {
    			 	$("#id_display").html(msg);
    			 	$("#id_display").css('background-color',"#000");
   			        if(msg.length>5)
    			        {
    			 	     $("#id_display").css({fontSize:"1300%"});
    			         }
    			        else
    			       {
    			  	$("#id_display").css({fontSize:"2000%"}); 
    			      }
 
    			 }
    			 else
    			 {
        			 var img_html = '<img alt="" src="img/christmas-message.png" height="767px" width="1532">';
        			 $("#id_display").html(img_html);
        			 ////$().css({'background-color', "#"}); 
        			 //document.body.style.background = 'red';
        		 }
        		
    			 func_load();
    		}
    	});
    }


</script>

</head>

<body onload="func_load();">
	<br/>
	<br/>
	
	
	<div class="Text" name="id_display" id="id_display">
		<?php if($display_now == 1){?>
		<?php echo $display_text;}else{?>
		<img alt="" src="img/christmas-message.png" >
		<?php }?>
	</div>


	</body>

</html>