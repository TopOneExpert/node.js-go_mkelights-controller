
<?php
/* @var $display_start_hour /includes/controller.php */
/* @var $display_end_hour /includes/controller.php */

?>

<div class="content_conf">
	<div class="left_side_wrapper">
		<div class="stream_wrapper">
<iframe width="610" height="368" src="https://www.youtube.com/embed/live_stream?channel=UCBvhQ79kUM0XmYP-BKpy3GQ&autoplay=1" frameborder="0" gesture="media" allowfullscreen></iframe>
<span style="color:white">The video may be delayed 5-10 seconds when you turn a light on or off.</span>
		</div>
		<?php include_once 'includes/controlButtons.php'; ?>

	</div>
	<div class="right_side_wrapper">
		<div class="time-header">
			<img src="images/time_header.png" alt="">
		</div>
		<div class="time_wrapper">
			<div class="controls-on-time">
				Controls ON at 5pm  and OFF at 9pm
			</div>
			<div class="current-time">
				Current Time: <?= $m_dt ?> CST
			</div>
		</div>

		<?php include_once 'includes/message.php'; ?>

		<?php include_once 'includes/messageSender.php'; ?>
	</div>
</div> <!-- end #content -->
