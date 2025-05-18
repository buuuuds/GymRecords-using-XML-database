<?php
// Set timezone to Philippines Time
date_default_timezone_set('Asia/Manila');

// Log all errors
error_reporting(E_ALL);  
ini_set('display_errors', 0);  // Don't display errors on the page
ini_set('log_errors', 1);  // Log errors to a file
ini_set('error_log', 'errors.log');  // Log errors to 'errors.log' file

include('db.php');  

$response = array('success' => false, 'error' => '', 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $username = trim($_POST['username']);
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if (empty($token) || empty($username) || empty($newPassword) || empty($confirmPassword)) {
        $response['error'] = 'All fields are required.';
        echo json_encode($response);
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        $response['error'] = 'Passwords do not match.';
        echo json_encode($response);
        exit();
    }

    // Verify token and retrieve associated email
    $stmt = $conn->prepare("SELECT email FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Check if username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response['error'] = 'Username already exists. Please choose a different one.';
            echo json_encode($response);
            exit();
        }

        // Update the username and password in the database
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, reset_token = NULL WHERE email = ?");
        $stmt->bind_param("sss", $username, $hashedPassword, $email);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Username and password updated successfully.';
        } else {
            $response['error'] = 'Failed to update the database. Please try again.';
        }
    } else {
        $response['error'] = 'Invalid reset token.';
    }

    // Return the JSON response
    echo json_encode($response);
}
