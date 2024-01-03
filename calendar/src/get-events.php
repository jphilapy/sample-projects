<?php
// Ensure that the request is sent via GET method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
	http_response_code(405); // Method Not Allowed
	exit();
}

require_once('db.php');

// Fetch events from the database
$sql = "SELECT id, title, event_start, event_end, event_category, event_color FROM events";
$result = $conn->query($sql);

// Check if the connection was successful
if ($result === false) {
	// Error occurred while querying the database
	$response = array('status' => 'error', 'message' => 'Failed to fetch events');
	echo json_encode($response);
	exit();
}

// Prepare an array to store the events
$events = array();

// Process each row from the result set
while ($row = $result->fetch_assoc()) {
	// Ensure that the event data is properly formatted
	$eventId = (int)$row['id'];
	$title = htmlspecialchars($row['title']);
	$start = htmlspecialchars($row['event_start']);
	$end = htmlspecialchars($row['event_end']);
	$category = htmlspecialchars($row['event_category']);
	$color = htmlspecialchars($row['event_color']);

	// Validate the event data
	if ($eventId <= 0 || empty($title) || empty($start) || empty($end)) {
		continue; // Skip invalid data and proceed to the next event
	}

	// Create an event object
	$event = array(
		'id' => $eventId,
		'title' => $title,
		'start' => $start,
		'end' => $end,
		'category' => $category,
		'color' => $color
	);
	// added T00:00 and T23:59 so that date is accurately represented on the calendar

	// Add the event to the events array
	array_push($events, $event);
}

// Convert the events array to JSON
$events = json_encode($events);

// Set the response header to JSON
header('Content-Type: application/json');

// Return the JSON response
echo $events;

// Close the database connection
$conn->close();
?>
