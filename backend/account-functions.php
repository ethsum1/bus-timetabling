<?php

// Checks for empty fields during user registration
function emptyInputRegister($name, $email, $pwd, $pwdRepeat) {
    return (empty($name) || empty($email) || empty($pwd) || empty($pwdRepeat));
}

// Checks for empty fields during user login
function emptyInputLogin($email, $pwd) {
    return (empty($email) || empty($pwd));
}

// Validates an email address input
function invalidEmail($email) {
    return (!filter_var($email, FILTER_VALIDATE_EMAIL));

}

// Checks that the password and password repeat fields match
function pwdMatch($pwd, $pwdRepeat) {
    return ($pwd !== $pwdRepeat);
}

// Checks the SQL statement is prepared correctly
function prepareStmt($stmt, $sql) {
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmtfailed");
        exit();
    } 
}

// Checks if an email already exists in the database
function emailExists($conn, $email) {
    $sql = "SELECT * FROM accounts WHERE email = ?;";
    $stmt = mysqli_stmt_init($conn);

    prepareStmt($stmt, $sql);
    
    // Binds parameters to the SQL statement
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

// Creates a new user in the database
function createUser($conn, $name, $email, $pwd) {

    // Uses prepared statements to prevent SQL injection attacks
    $sql = "INSERT INTO accounts (fname, email, pword) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    prepareStmt($stmt, $sql);

    // Hashes password so it isn't stored in the database in plain text
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPwd);
    mysqli_stmt_execute($stmt); 
    mysqli_stmt_close($stmt);

    // Finds the user ID of the newly created user
    $userRecord = emailExists($conn, $email);

    // Sets the user session variables to the values from the database record
    session_start();
    $_SESSION["user_fname"] = $userRecord["fname"];
    $_SESSION["user_email"] = $userRecord["email"];
    $_SESSION["user_id"] = $userRecord["account_id"];
    
    header("location: ../user-homepage.php");
    exit();
}

// Logs user into their existing account
function loginUser($conn, $email, $pwd) {
    $userRecord = emailExists($conn, $email);

    // Checks that the email entered does exist on the database
    if ($userRecord === false) {
        header("location: ../index.php?error=wronglogin");
        exit();
    }

    $pwdHashed = $userRecord["pword"];
    // Confirms password enters matches with hashed password in database
    $checkPwd = password_verify($pwd,$pwdHashed);

    // Creates session if passwords match, displays error if passwords don't match
    if ($checkPwd === false) {
        header("location: ../index.php?error=wronglogin");
        exit();
    }
    else if ($checkPwd === true) {
        session_start();
        $_SESSION["user_id"] = $userRecord["account_id"];
        $_SESSION["user_email"] = $userRecord["email"];
        $_SESSION["user_fname"] = $userRecord["fname"];

        header("location: ../user-homepage.php");
        exit();
    }
}

?>