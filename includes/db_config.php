<?php

$host = "localhost";
$dbname = "elite_hotel_system";
$username = "root"; 
$password = "";     


$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("ግንኙነቱ አልተሳካም: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
