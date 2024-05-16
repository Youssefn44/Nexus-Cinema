<?php
$servername = "localhost";
$username = "root";
$password = "Cinema999!";
$dbname = "cinema_site";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
