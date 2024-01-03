<?php
// Ensure that the request is sent via POST method
require_once ('GoogleCalendarClient.php');

// set these variables as part of setup
$calendar_id = ""; // get from your specific google calendar > settings
$user = ""; // get from Google Developer Console, Service Account email 

$calendar = new GoogleCalendarClient($calendar_id, 'America/New_York');
$colors = $calendar->getColors();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// Validate and sanitize the user input
	$title = isset($_POST['event_title']) ? trim($_POST['event_title']) : '';
	$description = isset($_POST['description']) ? trim($_POST['description']) : '';
	$start = isset($_POST['event_start']) ? trim($_POST['event_start']) : '';
	$end = isset($_POST['event_end']) ? trim($_POST['event_end']) : '';
	$color = isset($_POST['event_color']) ? trim($_POST['event_color']) : '';

	// Input validation
	if (empty($title) || empty($start) || empty($end) || empty($color)) {
		http_response_code(400); // Bad Request
		$message = "The title, start, end, and color fields are required.";
	} else {
		// Data validation and sanitization

		$start = strip_tags($start);
		$end = strip_tags($end);

		$calendar->setTitle(strip_tags($title));
		$calendar->setDescription(strip_tags($description));
		$calendar->setStartDate($start);
		$calendar->setEndDate($end);
		$calendar->setColor(strip_tags($color));
		$calendar->setUser(strip_tags($user));

		$status = $calendar->addEventToGoogle();

		if($status != "") {
			$message = "<div class='text-center'><h5>Success!</h5> Your selected this date time range: {$start} - {$end}. </br><strong>Visit</strong>: <a target='_blank' href='{$status}'>	event on google.</a></div>";
		} else {
			$message = "Failed to update your Google Calendar. See error_log.txt";
		}
	}
}

?>
