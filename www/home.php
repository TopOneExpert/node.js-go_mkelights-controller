
<?php
/* @var $display_start_hour /includes/controller.php */
/* @var $display_end_hour /includes/controller.php */

?>

<div class="content_conf">
	<div class="left_side_wrapper custom_side">
		<div class="stream_wrapper customstream mr-3">
<iframe id="button_iframe" src="https://www.mkelights.com/cam1.html" width="453" height="339" scrolling="no" frameborder="0" style="border: 0px none transparent;"></iframe>
<span style="color:white">Camera 1</span> 
		</div>
		<div class="stream_wrapper customstream">
<iframe id="button_iframe" src="https://www.mkelights.com/cam2.html" width="453" height="339" scrolling="no" frameborder="0" style="border: 0px none transparent;"></iframe>
			<span style="color:white">Camera 2</span>
		</div>
		<?php include_once 'includes/controlButtons.php'; ?>
	</div>
	<div class="wrapper">
		<div class="content_conf">
			<div class="right_side_wrapper custom_side">
				<!-- <div class="time-header">
					<img src="images/time_header.png" alt="">
				</div> -->
				<!-- <div class="time_wrapper">
					<div class="controls-on-time">
						Controls ON at <?= $display_start_hour ?>  and OFF at <?= $display_end_hour ?>
					</div>
					<div class="current-time">
						Current Time: <?= $m_dt ?> CST
					</div>
				</div> -->
				<div class="half_content">
					<div class="left_content mr-3">
						<?php include_once 'includes/message.php'; ?>
					</div>
					<div class="left_content rigt_content">
						<?php include_once 'includes/messageSender.php'; ?>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	
</div> <!-- end #content -->
