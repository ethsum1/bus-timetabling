<?php

    // Checks to see if the form has been submitted
    if (isset($_POST["search"])) {

        // Gets the postcode value from the form and removes spaces in the postcode
        $postcode = $_POST["postcode"];
        $postcode = str_replace(' ','',$postcode);

        // Creates the URL to the API endpoint
        $url = "https://api.postcodes.io/postcodes/".$postcode;

        // Recieves response from API
        $response = file_get_contents($url);

        // Formats response as a valid JSON response and decodes it into an array
        $response = "[".$response."]";
        $dataset = json_decode($response,true);

        // Accesses child values within the array
        foreach($dataset as $elem) {
            $status = $elem['status'];
            
            // Stores results from the array
            $result_array = $elem['result'];
            $longitude = $result_array['longitude'];
            $latitude = $result_array['latitude'];
            $country = $result_array['country'];
            $parish = $result_array['parish'];

        }

        // Set cookies for longitude and latitude so it can be passed to other pages
        // Stores cookies for 24 hours
        setcookie("longitude",$longitude,time()+86400,"/");
        setcookie("latitude",$latitude,time()+86400,"/");

        if ($status != 200) {
            header("location: ../bus-stop-selector.php?error="."true");
            exit();
        }

        // Redirects user
        header("location: ../bus-stop-selector.php");
        exit();

    }
    else {
        header("location: ../bus-stop-selector.php");
        exit();
    }


?>