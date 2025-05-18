<?php
include('db.php'); // Database connection

$response = array('success' => false, 'error' => '');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Correct file paths for including PHPMailer
require $phppath . 'PHPMailer.php';
require $phppath . 'SMTP.php';
require $phppath . 'Exception.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Sanitize the email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate email
    if (empty($email)) {
        $response['error'] = 'Please enter your email.';
        echo json_encode($response);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['error'] = 'Invalid email format.';
        echo json_encode($response);
        exit();
    }

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $resetToken = bin2hex(random_bytes(16)); // Generate a secure token for password reset

        // Save the reset token and expiry time in the database
        $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $resetToken, $email);
        $stmt->execute();

        // Send the password reset link via email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'jamlance1@gmail.com'; // Your SMTP username
            $mail->Password = 'oqfe acux fhds rmtv'; // Your App Password (Use the App Password if you have 2FA enabled)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('jamlance1@gmail.com', 'Hyper Gym');
            $mail->addAddress($email); // User's email address

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $resetLink = "http://localhost/Gym%20Membership/reset-passwords.html?token=$resetToken"; // Full URL for password reset
            $mail->Body = "Click here to reset your password: <a href='$resetLink'>$resetLink</a>";

            // Send email
            $mail->send();
            $response['success'] = true;
            $response['message'] = 'Password reset link sent to your email.';
        } catch (Exception $e) {
            $response['error'] = "Password reset email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $response['error'] = 'Email not found.';
    }

    echo json_encode($response);
}
?>
