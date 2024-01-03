<?php
require_once('db.php');

// Ensure that the request is sent via POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405); // Method Not Allowed
	exit();
}

// Validate and sanitize the user input
$eventID = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$updatedTitle = isset($_POST['title']) ? trim($_POST['title']) : '';
$updatedStart = isset($_POST['start']) ? trim($_POST['start']) : null;
$updatedEnd = isset($_POST['end']) ? trim($_POST['end']) : null;
$updatedCategory = isset($_POST['category']) ? trim($_POST['category']) : '';
$updatedColor = isset($_POST['color']) ? trim($_POST['color']) : '';
$actionType = isset($_POST['actionType']) ? trim($_POST['actionType']) : null;

// Input validation
if (empty($eventID) || empty($updatedTitle)) {
	http_response_code(400); // Bad Request
	echo json_encode(array('error' => 'Event ID and Title are required.'));
	exit();
}

// Data validation and sanitization
$updatedTitle = filter_var($updatedTitle, FILTER_SANITIZE_STRING);
$updatedStart = $updatedStart !== null ? filter_var($updatedStart, FILTER_SANITIZE_STRING) : null;
$updatedEnd = $updatedEnd !== null ? filter_var($updatedEnd, FILTER_SANITIZE_STRING) : null;
$updatedCategory = filter_var($updatedCategory, FILTER_SANITIZE_STRING);
$updatedColor = filter_var($updatedColor, FILTER_SANITIZE_STRING);
$actionType = filter_var($actionType, FILTER_SANITIZE_STRING);

// Prepare and execute the SQL statement to update the event in the database
if($actionType === "moveEvent") { // moving or resizing event
	logToFile(print_r([$updatedTitle,$updatedStart, $updatedEnd, $eventID], true));
	$stmt = $conn->prepare('UPDATE events SET title = ?, event_start = ?, event_end = ? WHERE id = ?');
	$stmt->bind_param('sssi', $updatedTitle,$updatedStart, $updatedEnd, $eventID);

} else if($updatedStart !== null && $updatedEnd !== null) {
	$stmt = $conn->prepare('UPDATE events SET title = ?, event_start = ?, event_end = ?, event_category = ?, event_color = ? WHERE id = ?');
	$stmt->bind_param('ssssi', $updatedTitle, $updatedStart, $updatedEnd, $updatedCategory, $updatedColor, $eventID);
} else {
	$stmt = $conn->prepare('UPDATE events SET title = ?, event_category = ?, event_color = ? WHERE id = ?');
	$stmt->bind_param('sssi', $updatedTitle, $updatedCategory, $updatedColor, $eventID);
}
// Execute the prepared statement
if ($stmt->execute()) {
	header('Content-Type: application/json');
	echo json_encode(array('message' => 'Event updated successfully.'));
} else {
	// Custom error message without revealing database errors
	http_response_code(500); // Internal Server Error
	echo json_encode(array('error' => 'Error updating event.'));
}

$stmt->close();
$conn->close();
?>
