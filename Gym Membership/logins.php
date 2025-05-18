<?php
// Include database connection
include('db.php');

// Start session to manage user login session
session_start();

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the posted data (username and password)
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validate if the fields are not empty
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'error' => 'Username and Password are required.']);
        exit;
    }

    // Prepare the SQL query to check the credentials
    $query = "SELECT id, username, password FROM users WHERE username = ?";

    if ($stmt = $conn->prepare($query)) {
        // Bind the parameters
        $stmt->bind_param("s", $username);
        
        // Execute the query
        $stmt->execute();
        
        // Store the result
        $stmt->store_result();
        
        // Check if the user exists
        if ($stmt->num_rows > 0) {
            // Bind the result to variables
            $stmt->bind_result($id, $db_username, $db_password);
            $stmt->fetch();
            
            // Verify the password using password_verify() function
            if (password_verify($password, $db_password)) {
                // Password matches, set the session variable
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $db_username;

                // Return success response
                echo json_encode(['success' => true, 'message' => 'Login successful']);
            } else {
                // Password doesn't match
                echo json_encode(['success' => false, 'error' => 'Invalid username or password.']);
            }
        } else {
            // Username not found
            echo json_encode(['success' => false, 'error' => 'Invalid username or password.']);
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Database query failed.']);
    }

    // Close the database connection
    $conn->close();
}
?>
