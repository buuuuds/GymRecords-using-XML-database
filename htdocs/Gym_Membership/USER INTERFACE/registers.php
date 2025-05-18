<?php

session_start();
$response = array('success' => false, 'error' => '');

// Database connection
include('db.php'); // Include your database connection file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if PHPMailer files exist
$phppath = __DIR__ . '/PHPMailer-master/src/';

if (!file_exists($phppath . 'PHPMailer.php')) {
    $response['error'] = "PHPMailer.php not found!";
    echo json_encode($response);
    exit();
}
if (!file_exists($phppath . 'SMTP.php')) {
    $response['error'] = "SMTP.php not found!";
    echo json_encode($response);
    exit();
}
if (!file_exists($phppath . 'Exception.php')) {
    $response['error'] = "Exception.php not found!";
    echo json_encode($response);
    exit();
}

// Include PHPMailer
require $phppath . 'PHPMailer.php';
require $phppath . 'SMTP.php';
require $phppath . 'Exception.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $response['error'] = 'Please fill in all fields.';
        echo json_encode($response);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['error'] = 'Invalid email format.';
        echo json_encode($response);
        exit();
    }

    if ($password !== $confirmPassword) {
        $response['error'] = 'Passwords do not match.';
        echo json_encode($response);
        exit();
    }

    // Check if username or email already exists in the `users` table only
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    if (!$stmt->execute()) {
        $response['error'] = 'Database Error: ' . $stmt->error;
        echo json_encode($response);
        exit();
    }
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['error'] = 'Username or email already exists in registered users.';
        echo json_encode($response);
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the temp_users table
    $stmt = $conn->prepare("INSERT INTO temp_users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    if (!$stmt->execute()) {
        $response['error'] = 'Database Error: ' . $stmt->error;
        echo json_encode($response);
        exit();
    }

    // Generate OTP
    $otp = rand(100000, 999999);

    // Store OTP in the session
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email; // Store email for OTP verification

    // Store OTP in the database
    $otpStmt = $conn->prepare("INSERT INTO user_otps (email, otp, created_at) VALUES (?, ?, NOW())");
    $otpStmt->bind_param("si", $email, $otp);
    if (!$otpStmt->execute()) {
        $response['error'] = 'Database Error: ' . $otpStmt->error;
        echo json_encode($response);
        exit();
    }

    // Send OTP email
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jamlance1@gmail.com'; // Your email address
        $mail->Password = 'oqfe acux fhds rmtv'; // Make sure this is correct
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('jamlance1@gmail.com', 'Hyper Gym');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP code is: <strong>$otp</strong>";

        if (!$mail->send()) {
            $response['error'] = 'OTP email could not be sent.';
            echo json_encode($response);
            exit();
        }

        $response['success'] = true;
        $response['message'] = 'Registration successful. Please check your email for OTP.';
    } catch (Exception $e) {
        // Enhanced error reporting
        $response['error'] = 'PHPMailer Error: ' . $e->getMessage();
        echo json_encode($response);
        exit();
    }

    echo json_encode($response);
}
?>
