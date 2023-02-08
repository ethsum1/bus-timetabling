<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link href="style/main.css" rel="stylesheet">
</head>
<body>

<?php


session_start();

echo "Testing purposes:- Name: ".$_SESSION["user_fname"].", Email: ".$_SESSION["user_email"];


if (isset($_SESSION['user_id'])) {
?>
    
    <div id="header-box">
        <h1>Bus Timetabling</h1>

        <div class="header-btn-group">
            <button class="btn btn-secondary"><a href="user-homepage.php">Your Homepage</a></button>
            <form action="backend/logout.php" action="post">
                <button class="btn btn-secondary" name="logout">Logout</button>
            </form>
        </div>

        <div class="current_user_info">
            <p >Current user set:</p>

            <?php
                echo "<p>User ID: ".$_SESSION["user_id"]."</p>";
                echo "<p>Name: ".$_SESSION["user_fname"]."</p>";
                echo "<p>Email: ".$_SESSION["user_email"]."</p>";
            ?>
        </div>

    </div>

<?php } ?>