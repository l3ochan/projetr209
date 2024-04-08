<?php
$db_host = "localhost";
$db_username = "ProjetR209User";
$db_password = "bMp9xL6S8TjhffSr";

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?> 