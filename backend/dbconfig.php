<?php

// Defines relevant variables
$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "bus-timetabling";

// Establishes connection with the database
$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

// Handles error if connection was unsuccessful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


?>