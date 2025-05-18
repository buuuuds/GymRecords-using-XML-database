<?php
header('Content-Type: application/json');



// Start the session
session_start();

// Initialize response array


// Database connection
include('db.php'); // Include your database connection file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Correct file paths for including PHPMailer
require '/home/username/public_html/htdocs/Gym Membership/PHPMailer-master/src/PHPMailer.php';
require '/home/username/public_html/htdocs/Gym Membership/PHPMailer-master/src/SMTP.php';
require '/home/username/public_html/htdocs/Gym Membership/PHPMailer-master/src/Exception.php';
$response = array('success' => false, 'error' => '');
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
    $stmt->execute();
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
    if ($stmt->execute()) {
        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP in the session
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email; // Store email for OTP verification

        // Store OTP in the database
        $otpStmt = $conn->prepare("INSERT INTO user_otps (email, otp, created_at) VALUES (?, ?, NOW())");
        $otpStmt->bind_param("si", $email, $otp);
        $otpStmt->execute();

        // Send OTP email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jamlance1@gmail.com';
            $mail->Password = 'oqfe acux fhds rmtv'; // Use environment variable in production
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
            $response['error'] = 'OTP email could not be sent. Error: ' . $mail->ErrorInfo;
            echo json_encode($response);
            exit();
        }
    } else {
        $response['error'] = 'Error occurred while registering.';
        echo json_encode($response);
        exit();
    }

    echo json_encode($response);
}
?>
