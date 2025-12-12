<?php
$host = "localhost";
$user = "root";          // Default XAMPP username
$pass = "";              // Default XAMPP password is empty
$db = "proyekakhir";   // <--- CHANGED THIS to match your DB name

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>