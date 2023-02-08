<?php

// Checks that the form has been submitted
if (isset($_POST["submit"])) {

    // Retrieves input fields from form
    $name = $_POST["name"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdRepeat"];

    require_once 'dbconfig.php';
    require_once 'account-functions.php';

    // Checks for potential errors and sends an error message if there is
    if (emptyInputRegister($name, $email, $pwd, $pwdRepeat) !== false) {
        header("location: ../index.php?error=emptyinput");
        exit();
    }
    if (invalidEmail($email) !== false) {
        header("location: ../index.php?error=invalidemail");
        exit();
    }
    if (pwdMatch($pwd, $pwdRepeat) !== false) {
        header("location: ../index.php?error=pwdmatch");
        exit();
    }
    if (emailExists($conn, $email) !== false) {
        header("location: ../index.php?error=emailexists");
        exit();
    }

    // Creates a user if no errors are found
    createUser($conn, $name, $email, $pwd);

}
// If the user accesses the webpage without submitting the form, it redirects them to the register form page
else {
    header("location: ../index.php");
    exit();
}

?>