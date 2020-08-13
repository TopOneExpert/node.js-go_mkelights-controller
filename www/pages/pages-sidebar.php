<?php
/* @var $display_start_hour /includes/controller.php */
/* @var $display_end_hour /includes/controller.php */

?>

<div class="time-header">
	<img src="/images/time_header.png" alt="">
</div>
<div class="time_wrapper">
	<div class="controls-on-time">
		Controls ON at <?= $display_start_hour ?>  and OFF at <?= $display_end_hour ?>
	</div>
	<div class="current-time">
		Current Time: <?= $m_dt ?> CST
	</div>
</div>
<div class="stream_wrapper">
 <script type="text/javascript" src="https://cdn.sender.net/webforms/7661/a319c844.js?v=6"></script> 
</div>