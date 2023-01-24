<?php
    include_once 'header.php'
?>

<h1>Please enter a postcode to display nearby services</h1>

<?php
if (isset($_GET["error"])) {
  if ($_GET["error"] == "404") {
    echo '<div class="alert alert-danger" role="alert">There was a problem with your postcode entry.</div>';
  }
}
if ((isset($_GET["lon"])) && (isset($_GET["lat"]))) {
$longitude = $_GET["lon"];
$latitude = $_GET["lat"];
  echo '<div class="alert alert-success" role="alert">There were successful returned results. Longitude: '.$longitude.'. Latitude: '.$latitude.'.</div>';
}
?>

<form action="includes/postcode-selector.inc.php" method="post">
    <input name="postcode" placeholder="Enter a postcode" type="text" class="form-control" label="postcode">
    <button name="search" class="btn btn-secondary my-2">Search</button>
</form>


<?php

function getDistance($lat1,$lon1,$lat2,$lon2) {
  // Source: geeks for geeks
  
  $lati1 = deg2rad($lat1);
  $long1 = deg2rad($lon1);
  $lati2 = deg2rad($lat2);
  $long2 = deg2rad($lon2);

  // Haversine formula
  $deglon = $long2 - $long1;
  $deglat = $lati2 - $lati1;

  $val = pow(sin($deglat/2),2)+cos($lati1)*cos($lati2)*pow(sin($deglon/2),2);
  $res = 2 * asin(sqrt($val));
  $radius = 3958.756;
  $mtokm = 1.609344;

  return ($res*$radius*$mtokm);
  
}

function array_push_assoc($array, $key, $value){
  $array[$key] = $value;
  return $array;
}

// Get all records from database, find distance and sort in ascending order of distance between long/lat
$sql = "SELECT * FROM bus_stops;";
$bus_stops_arr = array();
require_once 'includes/dbconfig.inc.php';
$result = mysqli_query($conn,$sql);

while($row = mysqli_fetch_array($result)) {

  $row_longitude = 0;
  $row_latitude = 0;
  $row_id = 0;
  $dist = 0;

  $row_longitude = $row['longitude'];
  $row_latitude = $row['latitude'];
  $row_id = $row['stop_id'];

  if ($row_longitude == 0 && $row_latitude == 0){
    exit();
  }

  $dist = getDistance($latitude,$longitude,$row_latitude,$row_longitude);

  $bus_stops_arr = array_push_assoc($bus_stops_arr, $row_id, $dist);
}
if ($row_longitude !== 0 && $row_latitude !== 0) {
  asort($bus_stops_arr);

  $closest_stop_id = array_key_first($bus_stops_arr);
  $closest_dist = reset($bus_stops_arr);
  
  $sql = "SELECT * FROM bus_stops WHERE stop_id=".$closest_stop_id.";";
  $result = mysqli_query($conn,$sql);
  
  while($row = mysqli_fetch_array($result)) {
    echo "Stop Name: ".$row["name"]."</br>";
      echo "Street: ".$row["street"]."</br>";
      echo "Town: ".$row["town"]."</br>";
      echo "Distance: ".$closest_dist." km.</br>";
  }

  echo "<form action='includes/bus-stop-page.inc.php?".$row["atco_code"]."' method='post'>";
  echo "<button name='search' class='btn btn-success my-2'>See bus stop page</button>";
  echo "</form>";

  ?>

  <?php
}



// $stmt = mysqli_stmt_init($conn);

//     if (!mysqli_stmt_prepare($stmt, $sql)) {
//         header("location: ../index.php?error=stmtfailed");
//         exit();
//     }
    
//     mysqli_stmt_bind_param($stmt, "i", $closest_stop_id);
//     mysqli_stmt_execute($stmt);

//     $result = mysqli_stmt_get_result($stmt);

//     $row = mysqli_fetch_assoc($result);

//     echo "Stop Name: ".$row["name"]."</br>";
//     echo "Street: ".$row["street"]."</br>";
//     echo "Town: ".$row["town"]."</br>";
//     echo "Distance: ".$closest_dist." metres.</br>";

mysqli_close($conn);


include_once 'footer.php'
?>