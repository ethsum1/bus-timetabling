<?php

// Defines relevant variables
$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "bus-timetabling";

try{
    // Establishes connection with the database
    $conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);
}
catch(Exception){
    // Handles error if connection was unsuccessful
    header('location: error.php?error=500');
    die("Connection failed: " . mysqli_connect_error());
}

?>