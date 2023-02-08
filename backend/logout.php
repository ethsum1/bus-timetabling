<?php
// Unset all of the session variables
session_start();
session_unset();

// Destroy the session   
session_destroy();
$_SESSION = array();

// Return to login page
header("location: ../index.php");
exit;

?>