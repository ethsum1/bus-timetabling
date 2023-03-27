<?php

session_start();
require_once 'dbconfig.php';

if (isset($_GET["id"])){
    $account_id = $_SESSION["user_id"];
    $stop_id = $_GET["id"];

    // Check if stop ID exists on saved ID table for logged in user
    $sql = "SELECT saved_id FROM saved_stops WHERE stop_id=$stop_id AND account_id=$account_id";
    $result = mysqli_query($conn,$sql);

    // If the stop is saved, then the record is deleted from the database
    if ($row = mysqli_fetch_assoc($result)) {
        $sql = "DELETE FROM saved_stops WHERE saved_id=".$row["saved_id"];
        $result = mysqli_query($conn,$sql);

    // If the stop doesn't exist on the database, it will be added
    } else {
        $sql = "INSERT INTO saved_stops(account_id,stop_id) VALUES ($account_id,$stop_id)";
        $result = mysqli_query($conn,$sql);
    }

    mysqli_close($conn); // Close db connection

    // Return to bus stop selector page
    header("location: ../bus-stop-selector.php");
    exit();
}

?>