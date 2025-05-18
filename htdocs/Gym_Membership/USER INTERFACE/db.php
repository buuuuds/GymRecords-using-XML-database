<?php
ini_set('display_errors', 0);
error_reporting(0);
$servername = "127.0.0.1:3306";
$username = "u627256117_root";; // Change this to your database username
$password = "Iloveyou143@@"; // Change this to your database password
$dbname = "u627256117_gym_membership"; // Change this to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
