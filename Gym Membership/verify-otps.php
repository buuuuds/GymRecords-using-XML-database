<?php
header('Content-Type: application/json');

// Enable error reporting for development

session_start();
$response = array('success' => false, 'error' => '');

// Database connection
include('db.php'); // Include your database connection file

if (!isset($_SESSION['otp']) || !isset($_SESSION['email'])) {
    $response['error'] = 'OTP session expired or not set.';
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = $_POST['otp'];

    // Validate OTP input
    if (empty($otp)) {
        $response['error'] = 'Please enter OTP.';
        echo json_encode($response);
        exit();
    }

    // Retrieve email from session
    $email = $_SESSION['email'];

    // Check if OTP matches the database record
    $stmt = $conn->prepare("SELECT * FROM user_otps WHERE email = ? AND otp = ? AND TIMESTAMPDIFF(MINUTE, created_at, NOW()) <= 10");
    $stmt->bind_param("si", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // OTP verified successfully, move user from temp_users to users
        $tempUserStmt = $conn->prepare("SELECT * FROM temp_users WHERE email = ?");
        $tempUserStmt->bind_param("s", $email);
        $tempUserStmt->execute();
        $tempUserResult = $tempUserStmt->get_result();

        if ($tempUserResult->num_rows > 0) {
            $tempUser = $tempUserResult->fetch_assoc();

            // Insert into users table
            $insertStmt = $conn->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
            $insertStmt->bind_param("sss", $tempUser['username'], $tempUser['email'], $tempUser['password']);
            if ($insertStmt->execute()) {
                // Delete user from temp_users
                $deleteStmt = $conn->prepare("DELETE FROM temp_users WHERE email = ?");
                $deleteStmt->bind_param("s", $email);
                $deleteStmt->execute();

                // Delete OTP after successful verification
                $deleteOtpStmt = $conn->prepare("DELETE FROM user_otps WHERE email = ?");
                $deleteOtpStmt->bind_param("s", $email);
                $deleteOtpStmt->execute();

                // Clear session variables
                unset($_SESSION['otp']);
                unset($_SESSION['email']);

                $response['success'] = true;
                $response['message'] = 'OTP verified successfully. Registration complete. You can now log in.';
            } else {
                $response['error'] = 'Failed to complete registration.';
            }
        } else {
            $response['error'] = 'Temporary user record not found.';
        }
    } else {
        $response['error'] = 'Invalid or expired OTP.';
    }

    echo json_encode($response);
}
?>
