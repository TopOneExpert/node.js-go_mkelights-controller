
<?php
/* @var $display_start_hour /includes/controller.php */
/* @var $display_end_hour /includes/controller.php */


$php_name = "manager";
include 'includes/mobile/header.php';
?>

<div class="content_conf">
	<div class="time_wrapper">
		<div class="controls-on-time">
			Controls ON at <?= $display_start_hour ?>  and OFF at <?= $display_end_hour ?>
		</div>
		<div class="current-time">
Current time:<form name="Tick" method="post">
<input type="text" size="11" name="Clock">
</form>
<script>
<!--
/*By George Chiang (JK's JavaScript tutorial)
http://javascriptkit.com
Credit must stay intact for use*/
function show(){
var Digital=new Date()
var hours=Digital.getHours()
var minutes=Digital.getMinutes()
var seconds=Digital.getSeconds()
var dn="AM"
if (hours>12){
dn="PM"
hours=hours-12
}
if (hours==0)
hours=12
if (minutes<=9)
minutes="0"+minutes
if (seconds<=9)
seconds="0"+seconds
document.Tick.Clock.value=hours+":"+minutes+":"
+seconds+" "+dn
setTimeout("show()",1000)
}
show()
//-->
</script>
		</div>
	</div>

	<div class="stream_wrapper">
	    <iframe id="vedio1" src="https://www.mkelights.com/cam1.html" width="98%" height="339" frameborder="0" name="myCam" style="display: none;transition: all 0.3s linear;"></iframe>

	    <iframe id="vedio2" src="https://www.mkelights.com/cam2.html" width="98%" height="339" frameborder="0" name="myCam" style="display: none;transition: all 0.3s linear;"></iframe> 
	    <!-- <center><span style="color:white">There is a 5-10 second broadcast delay!</span></center> -->
	    <div class="click_here">
	    	<a href="javascript:;" id="camera1">CLICK HERE TO VIEW CAMERA 2</a>
	    	<a href="javascript:;" id="camera2" style="display: none;">CLICK HERE TO VIEW CAMERA 1</a>
	    </div>
	</div>

	<?php include_once 'includes/mobile/controlButtons.php'; ?>
	
</div> <!-- end #content -->
<div class="force-mobile-link-wrapper">
		<a class="force-mobile-link" href="#">If you don’t want to view the mobile version, click here.</a>
	</div>


<?php include('includes/mobile/footer.php'); ?>

<script type="text/javascript">
	$(document).on('click','#camera1',function(){
		$("#vedio1").hide();
		$("#vedio2").show();
		$("#camera1").hide();
		$("#camera2").show();

		});
	$(document).on('click','#camera2',function(){
		$("#vedio2").hide();
		$("#vedio1").show();
		$("#camera1").show();
		$("#camera2").hide();
		});
</script>