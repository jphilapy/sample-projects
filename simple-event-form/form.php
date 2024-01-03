<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

$message = "";
$status = false;
$colors = [];

include_once 'src/add-event-google.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Google Form</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	<script src='src/assets/js/fullcalendar-6.1.9/dist/index.global.js'></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.7.0.min.js"
			integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


</head>
<body>

<div class="container mt-5">
	<div class="card mx-auto w-50">
		<div class="card-body">

            <?php if($message): ?>
                <div id="validationMessage" class="alert <?= $status ? 'alert-success' : 'alert-danger'; ?> alert-dismissible" role="alert">
                    <span id="validationMessageText"><?=$message;?></span>
                </div>
            <?php endif; ?>

			<form id="eventForm" method="post" novalidate>
				<div class="mb-3">
					<label for="event_title" class="form-label">Title</label>
					<input type="text" class="form-control" id="event_title" name="event_title" required>
					<input type="hidden" id="eventId">
				</div>
				<div class="form-group mb-3">
					<label for="description">Description</label>
					<textarea class="form-control" name="description" id="description" cols="30" rows="5"></textarea>
				</div>
                <div class="d-flex justify-content-evenly align-content-center">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="all_day_checkbox" value="false">
                        <label class="form-check-label" for="all_day_checkbox">
                            All day
                        </label>
                    </div>

                    <div class="mb-3 colored-box-container">
                        <div>
                            <label for="event_color" class="form-label">
                            <select id="event_color" class="form-select" name="event_color">
                                <option value="">-- Choose a Color --</option>
								<?php foreach($colors as $key=>$color): ?>
                                    <option value="<?=$key;?>" data-color="<?=$color;?>"><?=ucwords($color);?></option>
								<?php endforeach; ?>
                            </select>
                            </label>
                        </div>
                        <div id="selected_color" class="colored-box rounded" style="display: none;"></div>
                    </div>
                </div>

                <div class="d-flex justify-content-evenly">
                    <div class="form-group mb-3">
                        <label for="event_start">Start Date</label>
                        <input type="text" class="form-control flatpickr-input" id="event_start" name="event_start"
                               required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="event_end">End Date</label>
                        <input type="text" class="form-control flatpickr-input" id="event_end" name="event_end" required>
                    </div>

                </div>


				<div class="d-flex justify-content-end w-100">
					<div id="buttonContainer">
						<button type="submit" class="btn btn-success">Add event</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Bootstrap 5 JS and jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>


    document.addEventListener('DOMContentLoaded', function () {
        // Get the input element
        var event_start_input = document.getElementById('event_start');
        var event_end_input = document.getElementById('event_end');
        var event_color_select = document.getElementById('event_color');
        var allDayCheckbox = document.getElementById('all_day_checkbox');

        flatPikr(true);

        allDayCheckbox.addEventListener('change', function () {
            // Update Flatpickr options based on checkbox state
            if (allDayCheckbox.checked) {
                flatPikr(false);
                // Clear times if "All day" is clicked after selecting a time
                clearTime(event_start_input);
                clearTime(event_end_input);
            } else {
                flatPikr(true);
            }
        });

        // Handle the change event of the color dropdown
        event_color_select.addEventListener('change', function () {
            // Get the selected color
            var selected_color = event_color_select.options[event_color_select.selectedIndex].getAttribute('data-color');

            // Change the background color of the Flatpickr input
            event_color_select.style.backgroundColor = getCustomColor(selected_color);
            if(selected_color) {
                event_color_select.style.color = getTextColor(getCustomColor(selected_color));
            } else {
                event_color_select.style.color = '#000';
            }
        });

        function getCustomColor(colorName) {
            switch (colorName) {
                case 'bold blue':
                    return '#0000FF'; // Replace with the actual hex code for Bold Blue
                case 'bold red':
                    return '#FF0000'; // Replace with the actual hex code for Bold Red
                case 'bold green':
                    return '#00FF00'; // Replace with the actual hex code for Bold Green
                // Add more custom color names as needed
                default:
                    return colorName; // Set a default color if the name is not recognized
            }
        }

        function flatPikr(showTime) {
            let dateTimeFormate = "Y-m-d h:i K";
            if(!showTime) dateTimeFormate = "Y-m-d";
            flatpickr(event_start_input, {
                enableTime: showTime,  // Enable time selection
                dateFormat: dateTimeFormate,  // Set the date format
                // Add any additional options or configurations you need
            });

            flatpickr(event_end_input, {
                enableTime: showTime,  // Enable time selection
                dateFormat: dateTimeFormate,  // Set the date format
                // Add any additional options or configurations you need
            });
        }

        function clearTime(inputElement) {
            // Get the selected date
            var selectedDate = inputElement._flatpickr.selectedDates[0];

            // Set the selected date without the time
            inputElement._flatpickr.setDate(selectedDate);
        }

        function getTextColor(backgroundColor) {

            let color = 'white';
            if(backgroundColor.toLowerCase() === 'yellow') {
                color = 'black';
            }

            return color;
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</body>
</html>
