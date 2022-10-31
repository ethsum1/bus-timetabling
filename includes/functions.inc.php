<?php

function emptyInputRegister($name, $email, $pwd, $pwdRepeat) {
    $result = false;
    if (empty($name) || empty($email) || empty($pwd) || empty($pwdRepeat)) {
        $result = true;
    }
    return $result;
}

function emptyInputLogin($email, $pwd) {
    $result = false;
    if (empty($email) || empty($pwd)) {
        $result = true;
    }
    return $result;
}

function invalidEmail($email) {
    $result = false;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    }
    return $result;

}

function pwdMatch($pwd, $pwdRepeat) {
    $result = false;
    if ($pwd !== $pwdRepeat) {
        $result = true;
    }
    return $result;

}

function emailExists($conn, $email) {
    $sql = "SELECT * FROM accounts WHERE email = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmtfailed");
        exit();
    }
    
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

function createUser($conn, $name, $email, $pwd) {
    $sql = "INSERT INTO accounts (fname, email, pword) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmtfailed");
        exit();
    }
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Finds the user ID of the newly created user
    $response = emailExists($conn, $email);


    session_start();
    $_SESSION["user_fname"] = $response["fname"];
    $_SESSION["user_email"] = $response["email"];
    $_SESSION["user_id"] = $response["account_id"];
    header("location: ../user-homepage.php");
        exit();
}

function loginUser($conn, $email, $pwd) {
    $emailExists = emailExists($conn, $email);

    if ($emailExists === false) {
        header("location: ../index.php?error=wronglogin");
        exit();
    }

    $pwdHashed = $emailExists["pword"];
    $checkPwd = password_verify($pwd,$pwdHashed);

    if ($checkPwd === false) {
        header("location: ../index.php?error=wronglogin");
        exit();
    }
    else if ($checkPwd === true) {
        session_start();
        $_SESSION["user_id"] = $emailExists["account_id"];
        $_SESSION["user_email"] = $emailExists["email"];
        $_SESSION["user_fname"] = $emailExists["fname"];

        header("location: ../user-homepage.php");
        exit();
    }

}

?>