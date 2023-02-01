<?php

    if (isset($_POST["search"])) {
        // Gets user entered postcode and removes whitespace
        $postcode = $_POST["postcode"];
        $postcode = str_replace(' ','',$postcode);

        // Calls the API using the curl structure
        $url = "https://api.postcodes.io/postcodes/".$postcode;

        $response = file_get_contents($url);

        // Decodes the JSON object into a PHP array
        $response = "[".$response."]";
        $dataset = json_decode($response,true);

        foreach($dataset as $elem) {
            // status is a single string, so we can output it using 'echo'
            $status = $elem['status'];
            
            // result is an array of items, so can't just output via 'echo' use 'print_r' to print an array
            $result_array = $elem['result'];

            // now can reference each of the items in the 'result' array...
            $longitude = $result_array['longitude'];
            $latitude = $result_array['latitude'];
            $country = $result_array['country'];
            $parish = $result_array['parish'];

            echo $country." ".$parish." ".$longitude.' ',$latitude; echo "\n";         
        }

        setcookie("longitude",$longitude,time()+86400,"/");
        setcookie("latitude",$latitude,time()+86400,"/");

        header("location: ../bus-stop-selector.php");
        exit();

    }


?>