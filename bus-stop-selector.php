<?php
    include_once 'header.php'
?>

<section class="bus-stop-selector">
<h1>Please enter a postcode to display nearby bus stops</h1>

<?php
if (isset($_GET["error"])) {
  if ($_GET["error"] == "true") {
    echo '<div class="alert alert-danger" role="alert">There was a problem with your postcode entry. Please try again</div>';
  }
}

if ((isset($_COOKIE["longitude"])) && isset($_COOKIE["latitude"])) {
  $longitude = $_COOKIE["longitude"];
  $latitude = $_COOKIE["latitude"];
  echo '<div class="alert alert-success" role="alert">There were successful returned results. Longitude: '.$longitude.'. Latitude: '.$latitude.'.</div>';
}
?>

<form action="backend/postcode-selector.php" method="post">
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
require_once 'backend/dbconfig.php';
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
  $closest_dist_formatted = number_format($closest_dist, 2, '.', ',');
  
  $sql = "SELECT * FROM bus_stops WHERE stop_id=".$closest_stop_id.";";
  $result = mysqli_query($conn,$sql);
  
  while($row = mysqli_fetch_array($result)) {
    echo "Stop Name: ".$row["name"]."</br>";
    echo "Street: ".$row["street"]."</br>";
    echo "Town: ".$row["town"]."</br>";
    echo "Distance: ".$closest_dist_formatted." km.</br>";

    setcookie("stop_id",$row['stop_id'],time()+86400,'/');
    setcookie("atco_code",$row['atco_code'],time()+86400,'/');
  }

  echo "<a href='bus-stop-page.php'><button name='search' class='btn btn-success my-2'>See bus stop page</button></a>";

  echo "</section>";

}

mysqli_close($conn);


include_once 'footer.php'
?>