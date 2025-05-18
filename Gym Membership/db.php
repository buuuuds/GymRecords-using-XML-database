<?php
header('Content-Type: application/json'); // Tell the browser that the response is JSON

$servername = "localhost";
$username = "u627256117_gym_christ";
$password = "Admin12345678@@";
$dbname = "u627256117_gym_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Perform necessary database operations here

// Example: Sending a successful response
echo json_encode(['success' => true, 'message' => 'Connected successfully!']);
?>
