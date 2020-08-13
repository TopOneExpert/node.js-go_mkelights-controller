<?php
include_once 'model/Scheduler.php';

use admin\model\Scheduler;

$scheduler = new Scheduler;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link REL="SHORTCUT ICON" HREF="favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MKELights Admin</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/css/bootstrap-combined.min.css.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="/css/bootstrap-datetimepicker.min.css" media="screen"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <script>
  $(function() {
    $("#eventStartDate").datetimepicker({
		pickTime: false
    });
    $("#eventEndDate").datetimepicker({
		pickTime: false
    });
    $("#startTime").datetimepicker({
		pickDate: false,
	    pickSeconds: false
    });
    $("#endTime").datetimepicker({
	    pickDate: false,
	    pickSeconds: false
    });
    $("#marathonStart").datetimepicker({
	    pickSeconds: false
    });
    $("#marathonEnd").datetimepicker({
	    pickSeconds: false
    });

	$('#scheduleTimers').on('submit', function () {
		save_changes(this);
		return false;
	  });
  });


  
  function save_changes(form){
	var jsonString = JSON.stringify($(form).serializeArray());

   $.ajax({
        type: "POST",
        url: "process_schedule_change.php",
        data: {data : jsonString},
        cache: false,
        success: function(data){
            console.log(data);
			$("#save_status").html(data);

			setTimeout(function(){ $("#save_status").html(""); }, 5000);
        }
    });
  }
  </script>

</head>

<body>
<form id="scheduleTimers" action="process_schedule_change">
	<div>
		<h1>Timer schedule</h1>
	</div>
	<div class="well">
		<h2>Event date range:</h2>
		<div>From:</div>
		<div id="eventStartDate" class="input-append date datepicker">
			<input data-format="dd/MM/yyyy" type="text" name="eventStartDate" value="<?= $scheduler->eventStartDate ?>" />
			<span class="add-on">
				<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
			</span>
		</div>
		<div>To:</div>
		<div id="eventEndDate" class="input-append date datepicker">
			<input data-format="dd/MM/yyyy" type="text" name="eventEndDate" value="<?= $scheduler->eventEndDate ?>" />
			<span class="add-on">
				<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
			</span>
		</div>
	</div>

	<div class="well">
		<h2>Controls on:</h2>
		<div>From:</div>
		<div id="startTime" class="input-append date datepicker">
			<input data-format="hh:mm" type="text" name="startTime" value="<?= $scheduler->startTime ?>" />
			<span class="add-on">
				<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
			</span>
		</div>
		<div>To:</div>
		<div id="endTime" class="input-append date datepicker">
			<input data-format="hh:mm" type="text" name="endTime" value="<?= $scheduler->endTime ?>" />
			<span class="add-on">
				<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
			</span>
		</div>
	</div>

	<div class="well">
		<h2>Marathon:</h2>
		<div>From:</div>
		<div id="marathonStart" class="input-append date datepicker">
			<input data-format="dd/MM/yyyy hh:mm" type="text" name="marathonStart" value="<?= $scheduler->marathonStart ?>" />
			<span class="add-on">
				<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
			</span>
		</div>
		<div>To:</div>
		<div id="marathonEnd" class="input-append date datepicker">
			<input data-format="dd/MM/yyyy hh:mm" type="text" name="marathonEnd" value="<?= $scheduler->marathonEnd ?>" />
			<span class="add-on">
				<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
			</span>
		</div>
	</div>

	<button type="submit">Save</button>
	<div id="save_status"></div>
</form>
</body>
</html>
