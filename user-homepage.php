<?php
    include_once 'header.php';
    include_once 'backend/dbconfig.php';


    echo "<section class='page-content'>";
    echo "<h1 class='my-3 display-1'>Welcome ".$_SESSION["user_fname"]."!</h1>";

    $button = "";
    $button .= "<div class='homepage-btn'>";
    $button .= "<a href='bus-stop-selector.php'>";
    $button .= "<p>Choose a bus stop</p>";
    $button .= "<img src='media/bus_stop_icon.png'>";
    $button .= "</a></div>";
    echo $button;

    echo "<h2>Your saved bus stops</h2>";

    $account_id = $_SESSION["user_id"];
    $saved_stops = array();
    $i = 0;

    // Uses an inner join statement with a common key of the bus stop ID
    $sql = "SELECT * FROM bus_stops bs INNER JOIN saved_stops ss ON bs.stop_id = ss.stop_id WHERE ss.account_id = $account_id;";
    $result = mysqli_query($conn,$sql);
  
    // Returned data is stored to a temporary array
    while($row = mysqli_fetch_array($result)) {
        $saved_stops[$i][0] = $row["stop_id"];
        $saved_stops[$i][1] = $row["name"];
        $saved_stops[$i][2] = $row["street"];
        $saved_stops[$i][3] = $row["town"];
        $saved_stops[$i][4] = $row["indicator"];
        $i++;
    }
    mysqli_close($conn);

    echo "<div class='scrollbar'><div class='center'>";

    // Displays an error message is there were no saved stops found
    if (empty($saved_stops)) {
        echo "<div class='message'>You haven't saved any buses as favourites yet!</div>";
    } else {
        // Displays box of information for each saved stop
        for ($i=0;$i<count($saved_stops);$i++) {
            // Creates link to redirect the user to the bus stop page. Passes in the stop ID.
            echo "<a href='backend/bus-page-redirect.php?id=".$saved_stops[$i][0]."'><div class='container'>";
            echo "<h2>".$saved_stops[$i][1]."</h2>";
            echo "<p style='font-size: 15px'>(".$saved_stops[$i][4].")</p>";
            echo "<p>".$saved_stops[$i][2].",</p>";
            echo "<p>".$saved_stops[$i][3]."</p>";
            echo "</a></div>";
        }
    }
    echo "</div></div>";

    echo "<button class='btn btn-danger'><a href='backend/clear-saved-stops.php'>Clear all saved bus stops</a></button>";
    
    echo "</section>";
    include_once 'footer.php'
?>