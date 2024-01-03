<?php
// Ensure that the request is sent via POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405); // Method Not Allowed
	exit();
}

require_once('db.php');

// Validate and sanitize the user input
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$start = isset($_POST['start']) ? trim($_POST['start']) : '';
$end = isset($_POST['end']) ? trim($_POST['end']) : '';
$category = isset($_POST['category']) ? trim($_POST['category']) : '';
$color = isset($_POST['color']) ? trim($_POST['color']) : '';



// Input validation
if (empty($title) || empty($start) || empty($end)) {
	http_response_code(400); // Bad Request
	echo "The title, start, and end fields are required.";
	exit();
}

// Data validation and sanitization
$title = filter_var($title, FILTER_SANITIZE_STRING);
$start = filter_var($start, FILTER_SANITIZE_STRING);
$end = filter_var($end, FILTER_SANITIZE_STRING);
$color = filter_var($color, FILTER_SANITIZE_STRING);
$category = filter_var($category, FILTER_SANITIZE_STRING);

// Prepare and execute the SQL statement to insert the event into the database
$stmt = $conn->prepare('INSERT INTO events (title, event_start, event_end, event_category, event_color) VALUES (?, ?, ?, ?, ?)');
$stmt->bind_param('sssss', $title, $start, $end, $category, $color);

// Execute the prepared statement
if ($stmt->execute()) {
	echo "Event saved successfully.";
} else {
	// Custom error message without revealing database errors
	echo "Error saving event.";
}

$stmt->close();
$conn->close();
?>
