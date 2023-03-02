<?php

if (isset($_GET["id"])){
    setcookie("stop_id",$_GET["id"],time()+86400,'/');
    
    header("location: ../bus-stop-page.php");
    exit();
}

?>