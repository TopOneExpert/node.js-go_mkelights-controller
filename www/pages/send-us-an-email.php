<div class="left_side_wrapper--narrow">
	<?= require_once 'pages-sidebar.php' ?>
</div>
<div class="right_side_wrapper--wide">
	<div class="page-text-wrapper">
		<div class="page-text-inner-wrapper">
	<span style="font-weight:bold">Send Us An Email</span>
<p>If you'd like to reach out and contact us, please use the form below. We would love to hear from you and appreciate any feedback/questions!</p>
<form action="contact" method="post">
<p>
<b>Your Name:</b> <input type="text" name="yourname" /><br />
<b>Your E-mail:</b> <input type="text" name="email" /><br />
</p>
<p><b>Your comments:</b><br />
<textarea name="comments" rows="10" cols="50"></textarea></p>
	<input type="submit" name='submit' value="submit">
</form>

		</div>
	</div>
</div>
