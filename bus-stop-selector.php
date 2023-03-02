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

// Calculates distance between 2 longitude and latitude positions using Haversine formula
// Credit: Geeks For Geeks
function getDistance($lat1,$lon1,$lat2,$lon2) {
  
  $lati1 = deg2rad($lat1);
  $long1 = deg2rad($lon1);
  $lati2 = deg2rad($lat2);
  $long2 = deg2rad($lon2);

  $deglon = $long2 - $long1;
  $deglat = $lati2 - $lati1;

  $val = pow(sin($deglat/2),2)+cos($lati1)*cos($lati2)*pow(sin($deglon/2),2);
  $res = 2 * asin(sqrt($val));
  $radius = 3958.756;
  $mtokm = 1.609344;

  // Returns distance in kilometres
  return ($res*$radius*$mtokm);
  
}

// Function to 
function array_push_assoc($array, $key, $value){
  $array[$key] = $value;
  return $array;
}

// Get all records from database, find distance and sort in ascending order of distance between long/lat
$sql = "SELECT stop_id,longitude,latitude FROM bus_stops;";
$bus_stops_arr = array();
require_once 'backend/dbconfig.php';
$result = mysqli_query($conn,$sql);

// Loops through the result set row by row
while($row = mysqli_fetch_array($result)) {

  // Initialises and sets temporary variables
  $row_longitude = 0;
  $row_latitude = 0;
  $row_id = 0;
  $dist = 0;

  $row_longitude = $row['longitude'];
  $row_latitude = $row['latitude'];
  $row_id = $row['stop_id'];

  // If the longitude and latitude aren't stored, their data won't be added to the array
  if ($row_longitude == 0 && $row_latitude == 0){
    exit();
  }

  $dist = getDistance($latitude,$longitude,$row_latitude,$row_longitude);

  // Pushes each row ID and the distance between their longitude and latitude to the array
  // Uses the array_push_assoc function to ensure data is formatted in the array correctly
  $bus_stops_arr = array_push_assoc($bus_stops_arr, $row_id, $dist);
}
if ($row_longitude !== 0 && $row_latitude !== 0) {
  // Sorts the array in ascending order by distance
  asort($bus_stops_arr);

  // Selects the closest distance and its row ID.
  // $closest_stop_id = array_key_first($bus_stops_arr);
  closest_stops($bus_stops_arr,$conn);

  // $closest_dist = reset($bus_stops_arr);
  // $closest_dist_formatted = number_format($closest_dist, 2, '.', ','); // Formats distance to 2dp
  
  // $sql = "SELECT * FROM bus_stops WHERE stop_id=".$closest_stop_id.";";
  // $result = mysqli_query($conn,$sql);
  
  // // Gets data for the closest bus stop and displays it
  // while($row = mysqli_fetch_array($result)) {
  //   echo "Stop Name: ".$row["name"]."</br>";
  //   echo "Street: ".$row["street"]."</br>";
  //   echo "Town: ".$row["town"]."</br>";
  //   echo "Distance: ".$closest_dist_formatted." km.</br>";

  //   // Cookies are stored so they can be used in the timetable display page
  //   // Removes the need for a form to be used for a single button
  //   setcookie("stop_id",$row['stop_id'],time()+86400,'/');
  //   setcookie("atco_code",$row['atco_code'],time()+86400,'/');
  // }
  // echo "<a href='bus-stop-page.php'><button name='search' class='btn btn-success my-2'>See bus stop page</button></a>";
  echo "</section>";
}

function formatDist($dist){
  return round($dist*1000, -1)." m";
  // return number_format($dist*1000, 0, '.',',')."m";
}

// Allows parameter number of stops
function closest_stops($arr,$conn,$num = 5) {
  $i = 0;
  $closest_stops = array();
  foreach ($arr as $key => $value) {
    if ($i == $num) {
      break;
    }
    $closest_stops[$i][0] = $key;
    $closest_stops[$i][1] = $value;
    $i++;
  }

  echo "<table>";
  // echo "<tr><th>Number</th><th>Name</th><th>Street</th><th>Distance</th></tr>";

  $header = "";
  $header .= "<tr>";
  $header .= "<th>Number</th>";
  $header .= "<th>Name</th>";
  $header .= "<th>Street</th>";
  $header .= "<th>Distance</th>";
  $header .= "<th>Favourite</th>";
  $header .= "</tr>";

  echo $header;


  for ($i=0;$i<($num);$i++) {
    $sql = "SELECT * FROM bus_stops WHERE stop_id=".$closest_stops[$i][0];
    $result = mysqli_query($conn,$sql);
  
    while($row = mysqli_fetch_array($result)) {
      $closest_stops[$i][2] = $row["name"];
      $closest_stops[$i][3] = $row["street"];
      $closest_stops[$i][4] = $row["town"];
      $closest_stops[$i][5] = $row["longitude"];
      $closest_stops[$i][6] = $row["latitude"];
      $closest_stops[$i][7] = $row["atco_code"];
      $closest_stops[$i][8] = $row["indicator"];

    }

    $row = "";

    $row .= "<tr>";
    $row .= "<td>".($i+1)."</td>";
    $row .= "<td><a href='backend/bus-page-redirect.php?id=".$closest_stops[$i][0]."'>".$closest_stops[$i][2]." (".$closest_stops[$i][8].")</a></td>";
    $row .= "<td>".$closest_stops[$i][3]."</td>";
    $row .= "<td>".formatDist($closest_stops[$i][1])."</td>";
    $row .= "<td><i id='heart' class='fa-regular fa-heart'></i></td>";
    $row .= "</tr>";

    echo $row;
  }
  echo "</table>";
  



  $apiKey = 'AIzaSyAH1phNZY7PT6Wt9UhIWqi76NSQz0rSFWU'; // Replace with your Google Maps API key
  $markers = '';
  $markers .= '&markers=color:blue%7C'.$_COOKIE["latitude"].','.$_COOKIE["longitude"];

  for ($i=0;$i<$num;$i++) {
    $markers .= '&markers=color:red%7Clabel:'.($i+1).'%7C'.$closest_stops[$i][6].','.$closest_stops[$i][5];
  }

  $url = 'https://maps.googleapis.com/maps/api/staticmap?key='.$apiKey.'&size=600x400&maptype=roadmap'.$markers;
  echo "<img id='map' src='".$url."'>";


}


// $i = 0;
// $closest_stops = array();
// foreach ($bus_stops_arr as $key => $value) {
//   if ($i == 5) {
//     break;
//   }
//   $closest_stops[$i][0] = $key;
//   $closest_stops[$i][1] = $value;
//   $i++;
// }

// $sql = "SELECT * FROM bus_stops WHERE stop_id=";
// $sql .= $closest_stops[0][0];
// $sql .= " OR stop_id=";
// $sql .= $closest_stops[1][0];

// $result = mysqli_query($conn,$sql);


// while($row = mysqli_fetch_array($result)) {
//   echo "Stop Name: ".$row["name"]."</br>";
//   echo "Street: ".$row["street"]."</br>";
//   echo "Town: ".$row["town"]."</br>";
//   echo "Distance: ".$closest_dist_formatted." km.</br>";
// }
// print_r($closest_stops);
mysqli_close($conn);


// Create map

// function generateStaticMapURL() {
//   $apiKey = 'AIzaSyAH1phNZY7PT6Wt9UhIWqi76NSQz0rSFWU'; // Replace with your Google Maps API key
//   $markers = '';
//   $markers .= '&markers=color:blue%7C'.$_COOKIE["latitude"].','.$_COOKIE["longitude"];
  
//   $url = 'https://maps.googleapis.com/maps/api/staticmap?key='.$apiKey.'&size=600x400&maptype=roadmap'.$markers;
//   return $url;
// }

// $mapURL = generateStaticMapURL();
// echo "<img src='".$mapURL."'>";

include_once 'footer.php'
?>