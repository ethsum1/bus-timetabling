<?php
    // Unset all of the session variables.
    session_start();
    session_unset();
    // Finally, destroy the session.    
    session_destroy();
    $_SESSION = array();

    // Include URL for Login page to login again.
    header("location: ../index.php");
    exit;

?>