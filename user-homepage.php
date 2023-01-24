<?php
    include_once 'header.php';
    echo "Current user set as: ".$_SESSION["user_id"]." ".$_SESSION["user_fname"]." ".$_SESSION["user_email"];

?>

<h1 class="my-3 display-1">Welcome 
    <?php echo $_SESSION["user_fname"]; ?>
!</h1>



<form action="includes/logout.inc.php" action="post">
    <button class="btn btn-secondary" name="logout">Logout</button>
</form>

<h3>Your saved bus services:</h3>
<div class="user_homepage saved_buses">
    <a href="#x60">
        <div class="service">
            <div class="content">
                <p class="title">X60</p>
                <p class="description">BKM-AYL</p>                
            </div>
        </div>
    </a>
    <a href="#x60">
        <div class="service">
            <div class="content">
                <p class="title">X60</p>
            </div>           
        </div>
    </a>
    <a href="#x60">
        <div class="service">
            <div class="content">
                <p class="title">jwnfurfefenfue</p>
                <p class="description">BKM-AYL</p>
            </div>
        </div>
    </a>
    <a href="#x60">
        <div class="service">
            <p class="title">X60</p>
            <p class="description">BKM-AYL</p>
        </div>
    </a>
    <a href="#x60">
        <div class="service">
            <p class="title">X60</p>
            <p class="description">BKM-AYL</p>
        </div>
    </a>
    <a href="#x60">
        <div class="service">
            <p class="title">X60</p>
            <p class="description">BKM-AYL</p>
        </div>
    </a>



</div>

<button class="btn btn-primary" onclick="href('bus-stop-selector.php');">Choose a bus stop</button>
<button class="btn btn-primary">Choose a bus service</button>

<?php
    include_once 'footer.php'
?>