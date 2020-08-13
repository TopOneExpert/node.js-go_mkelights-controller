<?php
/* @var $display_start_hour /includes/controller.php */

?>

<script language="javascript" type="text/javascript">
	$( document ).ready(function() {
		var sd_enabled = 1;
		var sd_value = 15;
		var sd_timer;

		var messageTimer, resetMessageStatusTextTimer;

		var messageStatusElement = $('.message-status');
		var sendMessageButton = $('#btn-send-message');
		var messageInputField = $('#send-message-text');
		var messageElement = $('#christmas-message');

		function enableMessageSending  () {
			if (sendMessageButton.hasClass('disabled'))
				sendMessageButton.removeClass('disabled');

			sendMessageButton.on('click', (function (e) {
				e.preventDefault();
				send_display();
			}));
		}

		function disableMessageSending  () {
			sendMessageButton.addClass('disabled');
			sendMessageButton.off('click');
		}

		function enable_sd() {
			sd_enabled = 1;
			clearTimeout(sd_timer);
			enableMessageSending();
			messageInputField.prop('disabled', false);
			messageStatusElement.html("Ready...");
		}

		function update_sd() {
			clearTimeout(sd_timer);
			if (sd_value > 0) {
				sd_value = sd_value - 1;
				messageStatusElement.html("Sent!...wait for " + sd_value + " seconds..");
				sd_timer = setTimeout(update_sd, 1000);
			}
			else {
				enable_sd();
			}
		}

		function disable_sd() {
			sd_enabled = 0;
			sd_value = 15;
			messageStatusElement.html("Sent!...wait for " + sd_value + " seconds..");
			sd_timer = setTimeout(update_sd, 1000);

			messageInputField.prop('disabled', true);
			disableMessageSending();
		}

//			function clear_display() {
//				$('textarea#id_display').val("");
//			}

		// Christmas message sending
		function send_display() {
			if (sd_enabled === 0) {
				return;
			}
			clearTimeout(resetMessageStatusTextTimer);

			//alert("updating :: "+ m_gv_tbl_id);
			var ms = new Date().getTime().toString();
			var seed = "&seed=" + ms;

			messageStatusElement.html("Sending...");

			//alert(messageInputField.val());

			var cmd_name = "display";
			var cmd_value = messageInputField.val().replace('\n', '<br />');
			cmd_value = cmd_value.replace(/\n/g, '<br />');
			//alert(cmd_value);

			if (cmd_value.length === 0) {
				messageStatusElement.html("Error: Please Enter your message");
				resetMessageStatusTextTimer = setTimeout(function () {
					messageStatusElement.html("Ready...");
				},5000);
				return;
			}

			$.ajax({
				type: "GET",
				url: "ajaxRequests/cmd_ajax.php",
				data: "cmd=" + cmd_name + "&display_text=" + cmd_value + seed,
				success: function (msg) {
					//alert(msg );
					if (msg.indexOf("CMD-OK") > -1) {
						messageStatusElement.html("Sent.");
						messageInputField.val('');
						disable_sd();
					}
					else {
						//alert(msg);
						msg = msg.substring(7);
						if (msg.length > 1)
							messageStatusElement.html(msg);
						else
							messageStatusElement.html("Failed!");
					}
				}
			});
		}

		function checkMessage() {
			messageTimer = setTimeout(checkNewMessageRequest,1000);
		}

		function checkNewMessageRequest() {
			var defaultMessage = 'Merry Christmas!';

			//alert("updating :: "+ m_gv_tbl_id);
			var ms = new Date().getTime().toString();
			var seed = "&seed="+ms ;

//        $("#div_status").html("Sending...");

			$.ajax({
				type: "GET",
				url: "ajaxRequests/ajax_message.php",
				data: "cmd=get"+seed,
				success: function(msg){
					//alert(msg );
					var display_now = msg.substring(0,1);
					msg = msg.substring(1);
					//alert(display_now + " :: " + msg);
					if(msg.length > 5) {
						messageElement.css({fontSize:"100%"});
					}
					else {
						messageElement.css({fontSize:"200%"});
					}

					messageElement.html("");
					if(display_now === "1") {
						messageElement.html(msg);
						messageElement.css('background-color',"#000");

						if(msg.length>5)
							messageElement.css({fontSize:"100%"});
						else
							messageElement.css({fontSize:"200%"});
					}
					else {
						messageElement.css({fontSize: "100%"});
						messageElement.html(defaultMessage);
					}

					checkMessage();
				}
			});

		}

		<?php if($messages): ?>
		enableMessageSending();
		checkMessage();
		<?php else: ?>
		disableMessageSending();
		messageInputField.prop('disabled', true);
		messageStatusElement.html('Offline');
		<?php endif;?>
	});
</script>