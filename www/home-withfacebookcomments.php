
<?php
/* @var $display_start_hour /includes/controller.php */
/* @var $display_end_hour /includes/controller.php */

?>

<div class="content_conf">
	<div class="left_side_wrapper">
		<div class="stream_wrapper">
<iframe width="610" height="368" src="https://www.youtube.com/embed/S2ol4wK_S6U?autoplay=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
<span style="color:white">The video may be delayed about 10 seconds.</span>
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

<p>
    
</p><div class="send-message-wrapper">
<div class="fb-comments" data-href="https://www.mkelights.com/" data-colorscheme="dark" data-width="179" data-numposts="3"></div>
	</div>
	</div>
</div> <!-- end #content -->
