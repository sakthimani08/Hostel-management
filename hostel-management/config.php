<?php
$host = "localhost";
$user = "root";
$password = ""; // Default XAMPP MySQL has no password
$database = "hostel_db"; // 🔁 Change to your actual DB name

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
