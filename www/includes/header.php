<?php
$onlineText = '<span class="status status__online"> ONLINE </span>';
$offlineText = '<span class="status status__offline"> OFFLINE </span>';

?>

<div class="header">
	<div class="header-inside">
		<div class="logo">
			<img src="/images/mkelights_logo.png" alt="">
		</div>
		<div class="status-wrapper">
			SYSTEM STATUS: <?= $opened ? $onlineText : $offlineText ?>
		</div>
	</div>

	<ul class="menu">
		<li><a href="http://www.mkelights.com">Internet Controlled Christmas Lights</a></li>
		<li><a href="/how-does-this-work">How Does This Work?</a></li>
		<li><a href="/why-do-we-do-this">Why Do We Do This?</a></li>
		<li><a href="/other-christmas-fun">Other Christmas Fun</a></li>
		<li><a href="/send-us-an-email">Send Us An Email</a></li>
	</ul>
	<ul class="innermenu">
		<li><a href="javascript:;">Thanks for the fun! See you next year!</a></li>
	</ul>
</div> <!-- end #header -->