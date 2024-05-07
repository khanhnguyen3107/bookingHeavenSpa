<?php
// thực hiện kết nối db
$servername = "localhost";
$username = "root";
$password = "";
$database = "heaven_spa";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("CONNECTING FAID: " . $conn->connect_error);
}
?>