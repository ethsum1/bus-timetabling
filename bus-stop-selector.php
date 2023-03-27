<?php
    include_once 'header.php'
?>

<section class="page-content">
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
  $postcode = $_COOKIE["postcode"];
  // echo '<div class="alert alert-success" role="alert">There were successful returned results. Longitude: '.$longitude.'. Latitude: '.$latitude.'.</div>';
}

echo "<form class='postcode-entry' action='backend/postcode-selector.php' method='post'>";
echo "<input name='postcode' placeholder='Enter a postcode' type='text' class='form-control' label='postcode'>";
echo "<button name='search' class='btn btn-secondary my-2'>Search</button>";
echo "</form>";


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



  if (isset($postcode)) {
    echo "<p><strong>Displaying the closest bus stops to $postcode.</strong></p>";
    closestStops($bus_stops_arr,$conn);
  }  
  
  echo "</section>";
}

function formatDist($dist){
  return round($dist*1000, -1)." m";
  // return number_format($dist*1000, 0, '.',',')."m";
}

function displayHeart($stop_id,$conn){
  $sql = "SELECT * FROM saved_stops WHERE account_id=".$_SESSION["user_id"];
  $result = mysqli_query($conn,$sql);

  // Loops through the result set row by row
  while($row = mysqli_fetch_array($result)) {
    if ($row["stop_id"] == $stop_id) {
      return "<i class='fa-solid fa-heart'></i>";
      exit();
    }
  }
  // mysqli_close($conn);
  return "<i class='fa-regular fa-heart'></i>";
}

// Allows parameter number of stops
function closestStops($arr,$conn,$num = 5) {
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

  // Loops through each closest bus stop ID
  for ($i=0; $i<($num); $i++) {
    $sql = "SELECT * FROM bus_stops WHERE stop_id=".$closest_stops[$i][0];
    $result = mysqli_query($conn,$sql);

    // Adds database info for that stop ID to a temporary array
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

    // Displays a row of bus info for the stop ID
    $row .= "<tr>";
    $row .= "<td>".($i+1)."</td>";
    $row .= "<td><a href='backend/bus-page-redirect.php?id=".$closest_stops[$i][0]."'>".$closest_stops[$i][2]." (".$closest_stops[$i][8].")</a></td>";
    $row .= "<td>".$closest_stops[$i][3]."</td>";
    $row .= "<td>".formatDist($closest_stops[$i][1])."</td>";
    $row .= "<td><a href='backend/save-stop.php?id=".$closest_stops[$i][0]."'>".displayHeart($closest_stops[$i][0],$conn)."</a></td>";
    $row .= "</tr>";

    echo $row;
  }
  echo "</table>";
  


  $api_key = "[REDACTED]"; 

  $markers = '';
  // Gives marker for user entered postcode in a blue colour. Uses markers parameter
  $markers .= "&markers=color:blue%7C".$_COOKIE["latitude"].",".$_COOKIE["longitude"];

  // Adds each closest stops coordinate position to the marker parameter
  for ($i=0; $i<$num; $i++) {
    $markers .= "&markers=color:red%7Clabel:".($i+1)."%7C".$closest_stops[$i][6].",".$closest_stops[$i][5];
  }

  // Generates URL for image and displays image
  $url = "https://maps.googleapis.com/maps/api/staticmap?key=$api_key&size=600x400&maptype=roadmap$markers";

  echo "<img id='map' src='$url'>";

}

function closest_Stops($arr,$conn,$num = 5) {
  $i = 0;
  $closest_stops = array();
 
 
  // Loops through each key pair and stores in a temporary array until max num has been reached
  foreach ($arr as $key => $value) {
    if ($i == $num) {
      break;
    }
    $closest_stops[$i][0] = $key;
    $closest_stops[$i][1] = $value;
    $i++;
  }
  // Selects all data for all closest bus stops found
  $sql = "SELECT * FROM bus_stops WHERE stop_id=";
  for ($i=0;$i<($num-1);$i++) {
    $sql .= $closest_stops[$i][0];
    $sql .= " OR stop_id=";
  }
  $sql .= $closest_stops[$num-1][0];
  $result = mysqli_query($conn,$sql);
  // Stores each bus stop's data next to their bus stop ID in the array
  $i = 0;
  while($row = mysqli_fetch_array($result)) {
  
    $closest_stops[$i][2] = $row["name"];
    $closest_stops[$i][3] = $row["street"];
    $closest_stops[$i][4] = $row["town"];
    $closest_stops[$i][5] = $row["longitude"];
    $closest_stops[$i][6] = $row["latitude"];
    $closest_stops[$i][6] = $row["atco_code"];
 
 
    $i++;
  }
  echo "<table>";
  echo "<tr><th>Number</th><th>Name</th><th>Street</th><th>Distance</th></tr>";
  // Displays data in a table
  for ($i=0;$i<$num;$i++) {
    echo "<tr><td>".($i+1)."</td><td>".$closest_stops[$i][2]."</td><td>".$closest_stops[$i][0]."</td><td>".formatDist($closest_stops[$i][1])."</td></tr>";
  }
  echo "</table>";
 }
 


mysqli_close($conn);

include_once 'footer.php'
?>

