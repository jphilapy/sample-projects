<?php

// Database connection
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "calendar";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

function logToFile($message)
{
	$logFile = 'logfile.txt';
	$message = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
	file_put_contents($logFile, $message, FILE_APPEND);
}
?>
