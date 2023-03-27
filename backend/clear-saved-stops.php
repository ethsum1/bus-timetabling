<?php

session_start();
require_once 'dbconfig.php';

$account_id = $_SESSION["user_id"];
// Deletes all records from the table that match the logged in user's ID
$sql = "DELETE FROM saved_stops WHERE account_id=$account_id";
// Executes the DELETE command
$result = mysqli_query($conn,$sql);
mysqli_close($conn);

// Redirects user to the homepage
header("location: ../user-homepage.php");
exit();


?>