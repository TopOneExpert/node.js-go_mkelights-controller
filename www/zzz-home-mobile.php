
<?php
/* @var $display_start_hour /includes/controller.php */
/* @var $display_end_hour /includes/controller.php */


$php_name = "manager";
include 'includes/mobile/header.php';
?>

<div class="content_conf">
	<div class="time_wrapper">
		<div class="controls-on-time">
			Controls ON at <?= $display_start_hour ?>  and OFF at 6 am
		</div>
		<div class="current-time">
			Current Time: <?= $m_dt ?> CST
		</div>
	</div>

	<div class="stream_wrapper">
	    <iframe width="100%" height="368" src="https://www.youtube.com/embed/live_stream?channel=UCBvhQ79kUM0XmYP-BKpy3GQ&playsinline=1&autoplay=1" frameborder="0" gesture="media" allowfullscreen></iframe>
	    <center><span style="color:white">The stream may be 10 seconds delayed.</span></center>
	</div>

	<?php include_once 'includes/mobile/controlButtons.php'; ?>

	<div class="force-mobile-link-wrapper">
		<a class="force-mobile-link" href="#">If you don’t want to view the mobile version, click here.</a>
	</div>
</div> <!-- end #content -->


<?php include('includes/mobile/footer.php'); ?>