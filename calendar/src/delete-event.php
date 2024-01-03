<?php
// Ensure that the request is sent via POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405); // Method Not Allowed
	exit();
}

require_once('db.php');

// Retrieve the event ID from the POST data
$eventId = isset($_POST['id']) ? (int)$_POST['id'] : 0;

// Input validation
if ($eventId <= 0) {
	http_response_code(400); // Bad Request
	$response = array('status' => 'error', 'message' => 'Invalid event ID');
	echo json_encode($response);
	exit();
}

// Check if the connection was successful
if ($conn->connect_errno) {
	// Error occurred while connecting to the database
	$response = array('status' => 'error', 'message' => 'Failed to connect to the database');
	echo json_encode($response);
	exit();
}

// Prepare the SQL statement to delete the event
$sql = "DELETE FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);

// Bind the event ID parameter
$stmt->bind_param('i', $eventId);

// Execute the statement
if ($stmt->execute()) {
	// Event deleted successfully
	$response = array('status' => 'success', 'message' => 'Event deleted successfully');

	header('Content-Type: application/json');
	echo json_encode($response);
} else {
	// Error occurred while deleting the event
	$response = array('status' => 'error', 'message' => 'Failed to delete event');

	header('Content-Type: application/json');
	echo json_encode($response);
}

// Close the statement and database connection
$stmt->close();
$conn->close();
?>
