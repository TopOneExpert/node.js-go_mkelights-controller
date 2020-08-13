<?php
/* @var $display_start_hour /includes/controller.php */

?>


<script language="javascript" type="text/javascript">
	$( document ).ready(function() {
		var bt_enabled = 1;
		var bt_reset_sec = 5;
		var bt_timer;

		var reloadTimer;

		<?php if($stateChangeCountdown !== false): ?>
		clearTimeout(reloadTimer);

		var stateChangeCountdown = (<?= $stateChangeCountdown ?>) * 1000;

		reloadTimer = setTimeout(function(){ location.reload(); }, stateChangeCountdown);

		<?php endif; ?>

		var offlineStatusText = "<?= $offlineText ?>";

		var controlButtonsWrapper = $('.control-button');
		var controlButtons = $('.control-button a');
		var controlStatusElement = $('.control-status');

		function enableControlButtons () {
			if (controlButtonsWrapper.hasClass('disabled'))
				controlButtonsWrapper.removeClass('disabled');

			controlButtons.on('click', (function(e) {
				var button = $(e.target);

				var commandName = button.attr('data-command-name');
				var commandValue = button.attr('data-command-value');

				e.preventDefault();
				send_command(commandName, commandValue);
			}));
		}

		function disableControlButtons () {
			controlButtonsWrapper.addClass('disabled');
			controlButtons.off('click');
		}

		function disable_bt() {
			bt_enabled = 0;
			bt_reset_sec = 5;
			disableControlButtons();
		}

		function update_bt(status) {
			clearTimeout(bt_timer);

			if (bt_reset_sec > 0) {
				bt_reset_sec = bt_reset_sec - 1;
				controlStatusElement.html(status + "!...wait for " + bt_reset_sec + " seconds..");
				bt_timer = setTimeout(function(){ update_bt(status); }, 1000);
			}
			else {
				enable_bt();
				resetControlStatusMessage();
			}
		}

		function enable_bt() {
			bt_enabled = 1;
			clearTimeout(bt_timer);

			enableControlButtons();
		}

		function resetControlStatusMessage() {
			controlStatusElement.html("Ready...");
		}

		function send_command(device_id, device_state) {
			if (bt_enabled === 0) {
				//alert("Try after some time...");
				return;
			}
			disable_bt();
			//alert("updating :: "+ m_gv_tbl_id);
			var ms = new Date().getTime().toString();
			var seed = "&seed=" + ms;

			controlStatusElement.html("Sending...");

			var cmd_name = "send";
			//  /cmd_ajax.php?cmd=send&device_id=A1&device_state=1
			$.ajax({
				type: "GET",
				url: "ajaxRequests/cmd_ajax.php",
				data: "cmd=" + cmd_name + "&device_id=" + device_id + "&device_state=" + device_state + seed,
				success: function (msg) {
					//alert(msg );
					if (msg.indexOf("CMD-OK") > -1) {
						update_bt("Sent");
					}
					else {
						update_bt("Failed");
					}
				}
			});
		}

		<?php if($opened): ?>
			enableControlButtons();
		<?php else: ?>
			bt_enabled = 0;

			controlStatusElement.html(offlineStatusText);

			disableControlButtons();
		<?php endif;?>
	});

</script>