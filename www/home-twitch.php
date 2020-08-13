
<?php
/* @var $display_start_hour /includes/controller.php */
/* @var $display_end_hour /includes/controller.php */

?>

<div class="content_conf">
	<div class="left_side_wrapper">
		<div class="stream_wrapper">
		   <iframe src="https://player.twitch.tv/?channel=mkelights" frameborder="0" allowfullscreen="true" scrolling="no" height="368" width="610"></iframe><a href="https://www.twitch.tv/mkelights?tt_content=text_link&tt_medium=live_embed" style="padding:2px 0px 4px; display:block; width:345px; font-weight:normal; font-size:10px; text-decoration:underline;"></a>
<span style="color:white">There is a 5-10 second broadcast delay!</span>
		</div>
		<?php include_once 'includes/controlButtons.php'; ?>

	</div>
	<div class="right_side_wrapper">
		<div class="time-header">
			<img src="images/time_header.png" alt="">
		</div>
		<div class="time_wrapper">
			<div class="controls-on-time">
				Controls ON at <?= $display_start_hour ?>  and OFF at <?= $display_end_hour ?>
			</div>
			<div class="current-time">
				Current Time: <?= $m_dt ?> CST
			</div>
		</div>

		<?php include_once 'includes/message.php'; ?>

		<?php include_once 'includes/messageSender.php'; ?>
	</div>
</div> <!-- end #content -->
