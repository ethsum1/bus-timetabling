<?php

// Checks to see if form was submitted
if (isset($_POST["submit"])) {

    // Retrieves relevant inputs from the form
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];

    // Includes relevant requried files
    require_once 'dbconfig.inc.php';
    require_once 'functions.inc.php';

    // Redirects to login page with error message if inputs are empty
    if (emptyInputLogin($email, $pwd) !== false) {
        header("location: ../index.php?error=emptyinput");
        exit();
    }

    // Logs in the user
    loginUser($conn,$email,$pwd);
}
// Redirects to login page if form was not submitted
else {
    header("location: ../index.php");
    exit();
}


?>