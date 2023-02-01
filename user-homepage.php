<?php
    include_once 'header.php';
?>

<h1 class="my-3 display-1">Welcome 
    <?php echo $_SESSION["user_fname"]; ?>
!</h1>



<!-- <h3>Your saved bus services:</h3>
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



</div> -->
<div class="homepage-btn">
    <a href="bus-stop-selector.php">
        <p>Choose a bus stop</p>
        <img src="media/bus_stop_icon.png">
    </a>
</div>

<!-- <button class="btn btn-primary" onclick="href('bus-stop-selector.php');">Choose a bus stop</button>
<button class="btn btn-primary">Choose a bus service</button> -->

<?php
    include_once 'footer.php'
?>