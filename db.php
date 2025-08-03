<?php
$host = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "find_it_db";

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
