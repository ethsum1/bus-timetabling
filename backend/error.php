<?php

if (isset($_GET["error"])) {
    if ($_GET["error"] == "404") {
      echo "<h1>404: This webpage was not found</h1>";
      echo "<p>To return to the homepage, <a href='../index.php'>click here.</a></p>";
    }

    else if ($_GET["error"] == "500") {
        echo "<h1>505: Server error</h1>";
        echo "<p>To return to the homepage, <a href='../index.php'>click here.</a></p>";
    }

    else {
        echo "There was a problem.";
        echo "<p>To return to the homepage, <a href='../index.php'>click here.</a></p>";
    }
}
else {
    echo "There was a problem.";
    echo "<p>To return to the homepage, <a href='../index.php'>click here.</a></p>";
}

?>