<?php
// Database Configuration
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "shalu";

// Create connection
$con = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$con) {
    die("❌ Database connection failed: " . mysqli_connect_error());
}

// Optional: For debugging, you can enable this during setup
// echo "✅ Database connected successfully!";
?>
