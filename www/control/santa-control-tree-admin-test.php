<?php
include("./include/connect_db.php");
include("./function/html_component.php");
$data_tb_company = tb_company();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link REL="SHORTCUT ICON" HREF="favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TheSantaTracker.com :: Santa's Controllable Christmas Tree</title>
<meta name="Keywords" content="northpole, north pole, official, rudolph, santa, santa tracker, tracker, christmas, tree, santa's place, control, controllable, tree, on, off, ustream, thesantatracker, north, pole" />
<meta name="Description" content="Control Santa's Christmas Tree every night during the month of December from 6pm-10pm CST " />
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script>
  $(function() {
    $( ".datepicker" ).datepicker();
  });
  
  function save_changes(){
	console.log("Saving started");
	is_always_active = $("input:radio[name=always_active]:checked").val(); //0 or 1
	
	//console.log("Always active: "+is_always_active);
	
	
	read_changes_array = ['all_day_activated', 'time_of_day_select', 'hour_range_from', 'minute_range_from', 'hour_range_to', 'minute_range_to'];
	output_array = [];
	for(j=0; j<read_changes_array.length; j++){
		data_array = document.getElementsByClassName(read_changes_array[j]);
	
		data_values='';
		for(i=0; i<data_array.length; i++){
			if(i+1 != data_array.length)	{
				data_value = data_array[i].value;
				if(data_value != "") data_values+= data_value + ', ';
				else data_values+=  'NULL, ';
			}
			else{
				data_value = data_array[i].value;
				if(data_value != "") data_values+= data_value;
				else data_values+=  'NULL';
			}
		}
		//console.log(read_changes_array[j] + ': ' +data_values);
		
		output_array[j]= data_values.split(', ');
		
	}
	output_array[j]=[is_always_active];
	
	//console.log(output_array);
	
	var jsonString = JSON.stringify(output_array);
   $.ajax({
        type: "POST",
        url: "process_schedule_change_test.php",
        data: {data : jsonString}, 
        cache: false,

        success: function(data){
            //console.log("Data sent");
			$("#save_status").html(data);
			
			setTimeout(function(){ $("#save_status").html(""); }, 5000);
        }
    });
  }
  </script>

</head>

<body>
<?php include_once("analyticstracking.php") ?>
<div id="container">
    <div id="header">
		<div id="logo_top">
			<img src="images/logo.png" width="240" height="102" alt="The Santa Tracker logo" />		
		</div>
		<!--
		<div id="nav-menu-header">
			<ul>
				<li><a href="#" style="text-decoration:none; border-style:none;" ><img src="images/menu-home.png" width="85" height="102" border="0" alt="Santa Tracker Home" onmouseover="this.src='images/menu-home-click.png';"
      onmouseout="this.src='images/menu-home.png'" /></a></li>
				<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-kitchen.png" width="110" height="102" border="0" alt="Mrs Claus Kitchen"  onmouseover="this.src='images/menu-kitchen-click.png';"
      onmouseout="this.src='images/menu-kitchen.png'" /></a></li>
				<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-workshop.png" width="85" height="102" border="0" alt="Visit the Elves in Santas Workshop" onmouseover="this.src='images/menu-workshop-click.png';"
      onmouseout="this.src='images/menu-workshop.png'" /></a></li>
				<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-tracker.png" width="85" height="102" border="0" alt="Official Santa Tracker" onmouseover="this.src='images/menu-tracker-click.png';"
      onmouseout="this.src='images/menu-tracker.png'" /></a></li>
				<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-post-office.png" width="115" height="102" border="0" alt="Send Santa a Letter" onmouseover="this.src='images/menu-post-office-click.png';"
      onmouseout="this.src='images/menu-post-office.png'" /></a></li>
				<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-santas-place.png" width="115" height="102" border="0" alt="Santas Place" onmouseover="this.src='images/menu-santas-place-click.png';"
      onmouseout="this.src='images/menu-santas-place.png'" /></a></li>
				<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-reindeer-barn.png" width="125" height="102" border="0" alt="Reindeer Barn" onmouseover="this.src='images/menu-reindeer-barn-click.png';"
      onmouseout="this.src='images/menu-reindeer-barn.png'" /></a></li>
			</ul>
	  </div>
	  -->
	  <? echo menu_top();?>
    </div>

  	<div id="divider-container"></div>

    <div id="content">
	  <img src="images/bgn-content.jpg" />
	  <div id="content-title">
                <div class="pageheader">
                         <div class="text"><span class="shadow">CONTROL SANTAS CHRISTMAS TREE</span></div>
                </div>
</div>
	  <div id="content-middle">
	  	<div id="santa-place-control-tree-running-text" class="text4">
			<marquee><b>Thanks for another great Christmas! See you Dec 1st, 2015!</b></marquee>
		</div>
		<div id="santa-place-control-tree-content-1" style="height:936px;">
			<div id="santa-place-control-tree-content-1-left">
			  <iframe src="http://www.ustream.tv/embed/9539366" width="608" height="368" scrolling="no" frameborder="0" style="border: 0px none transparent;"></iframe>
			  <div id="santa-place-control-tree-content-2-left">
				<div id="santa-place-control-tree-content-2-left-in">
				 <!-- <iframe src="http://controllablechristmastree.com/srv_backup/button.php" width="608" height="300" scrolling="no" frameborder="0" style="border: 0px none transparent;"></iframe>-->
					<div id="santa-place-control-tree-button-area">
						<div id="santa-place-control-tree-button-area-header"><span id="timer_status">Admin area</span></div>	
						<div id="tree_control_santa_message" style="height:500px;">
							<iframe src="http://treecontrol.troublesomestudios.com/srv_backup/santa-control-tree-schedule-admin-test.php" width="500" height="500" scrolling="no" frameborder="0" style="border: 0px none transparent;"></iframe>
						</div>
					</div>
					</div>

				</div>
				</div>
            <div id="santa-place-control-tree-content-1-right">
				<div id="tree_control_holder">
					<div id="tree_control_header">
						Use the Controls Below
					</div>
					<div id="tree_control_button_holder">
						<iframe src="http://treecontrol.troublesomestudios.com/srv_backup/button2.php" width="290" height="248" scrolling="no" frameborder="0" style="border: 0px none transparent;"></iframe>
					</div>
				</div>
				
				  <iframe width="308" scrolling="no" height="115" frameborder="0" style="border: 0px none transparent; margin-bottom: 4px; margin-top:4px; background: #500403" src="http://treecontrol.troublesomestudios.com/srv_backup/santa-control-tree-sd2.php"></iframe>          
					<a href="/radio" target="blank"><center><img src="images/dj_jingles_banner.png" alt="Listen to North Pole Radio"></center></a>
					<!--<iframe id="tree_control_radio_player" src="http://eclipse.wavestreamer.com:5479/Live" height="40" frameborder="0"></iframe>-->
					<a href="#" onclick="javascript:window.open('how-to-use.php','popup','width=300,height=690');"><img src="images/learn2.png" /></a>
            </div>
		</div>
      </div>
      
        <div id="divider-content-middle"></div>
  </div>
      <!--
	 <div id="bottom-content">
        <div id="bottom_content_sub1">
          <div id="content-bottom-1"> <a href="#" style="text-decoration:none; border-style:none"><img src="images/content-bottom-1.jpg" width="275" height="108" border="0" alt="Santas Internet Controllable Christmas Tree"/></a> </div>
          <div id="content-bottom-2"> <a href="#" style="text-decoration:none; border-style:none"><img src="images/content-bottom-2.jpg" width="275" height="108" border="0" alt="Adopt a North Pole Elf Buddy"/></a> </div>
        </div>
	    <div id="bottom_content_sub2"> <a href="#" style="text-decoration:none; border-style:none"><img src="images/content-bottom-3.jpg" width="330" height="108" border="0" alt="Write Santa a letter in the North Pole"/></a> </div>
      </div>
	  <img src="images/end-content-bottom.jpg" width="960" height="6" />
      
    --> 
    
    <? echo bottom_content();?> 
	<div id="divider-container"></div>
	<!--
    <div id="ads-bottom-1">
		<div id="sub-ads-bottom1">
			<a href="#" style="text-decoration:none; border-style:none"><img src="images/ads-bottom-1.jpg" width="725" border="0" /></a>		</div>
		<div id="logo-ads-bottom1">
			<img src="images/logo-bottom.png" width="235" height="104" alt="The Santa Tracker logo"/>		</div>
    </div> 

    <div id="ads-bottom-2">
    
    </div>    
    -->
    <? echo ads_bottom();?>
	<div id="divider-container">
    <!-- divider content -->
    </div>
    <!--
    <div id="footer">
		<div id="nav-menu-bottom">
		<ul>
			<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-bottom-home.png" width="40" height="16" border="0" alt="Head back to The Santa Tracker homepage"/></a></li>
			<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-bottom-kitchen.png" width="75" height="16" border="0" alt="Visit Mrs Claus' Kitchen Recipes" /></a></li>
			<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-bottom-workshop.png" width="80" height="16" border="0" alt="Visit the Elves in the North Pole Workshop"/></a></li>
			<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-bottom-tracker.png" width="58" height="16" border="0" alt="Track Santa with the Official North Pole Santa Tracker"/></a></li>
			<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-bottom-post-office.png" width="97" height="16" border="0" alt="Write a Letter to Santa in the North Pole"/></a></li>
			<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-bottom-santas-place.png" width="95" height="16" border="0" alt="Visit Santas House"/></a></li>
			<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-bottom-reindeer-barn.png" width="110" height="16" border="0" alt="Check out Santas North Pole Reindeer Barn"/></a></li>
			<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-bottom-submit-content.png" width="120" height="16" border="0" /></a></li>
			<li><a href="#" style="text-decoration:none; border-style:none"><img src="images/menu-bottom-privacy-policy.png" width="120" height="16" border="0" /></a></li>
		</ul>
		</div> 
		<div id="copyright">
			<img src="images/copyright-bottom.png" width="165" height="16" />		</div>
	</div>
	-->
	<? echo footer_menu();?>
</div>
</body>
</html>
