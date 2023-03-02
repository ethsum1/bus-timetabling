<!DOCTYPE html>
<head>
    <!-- Bootstrap files -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b7d3758c86.js" crossorigin="anonymous"></script>
    <!-- Shared styling and JS -->
    <link href="style/main.css" rel="stylesheet">
    <script src="script/script.js"></script>
</head>
<body>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Starts session (account login) on every page
session_start();

// Only displays if the user is logged in
if (isset($_SESSION['user_id'])) {
    
?>
    <div id="header-box">
        <h1>Bus Timetabling</h1>
        <!-- Displays buttons for user interactivity -->
        <div class="header-btn-group">
            <button class="btn btn-secondary"><a href="user-homepage.php">Your Homepage</a></button>
            <form action="backend/logout.php" action="post">
                <button class="btn btn-secondary" name="logout">Logout</button>
            </form>
        </div>

        <!-- Displays current user info (for testing purposes) -->
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