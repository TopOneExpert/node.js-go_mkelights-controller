<?php

?>

<div class="message-wrapper">
	<div class="message-wrapper-inner">
		<div class="message-wrapper-text">
			<span id=""><div class="time_wrapper">
					<div class="controls-on-time">
						Controls ON at <?= $display_start_hour ?>  and OFF at <?= $display_end_hour ?>
					</div>
					<div class="current-time">
						Current Time: <?= $m_dt ?> CST
					</div>
				</div></span>
		</div>
	</div>
</div>
<div class="audio_panel">
<audio controls autoplay loop preload="metadata" style=" width:337px;">
	<source src="https://www.mkelights.com/media/WeWishYou.mp3" type="audio/mpeg">
	Your browser does not support the audio element.
</audio><br />
</div>