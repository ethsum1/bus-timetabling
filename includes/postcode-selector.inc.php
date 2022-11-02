<?php

    if (isset($_POST["search"])) {
        // Gets user entered postcode and removes whitespace
        $postcode = $_POST["postcode"];
        $postcode = str_replace(' ','',$postcode);

        // Calls the API using the curl structure
        $postcode="MK111JL";
        $url = "https://api.postcodes.io/postcodes/".$postcode;

        $curl = curl_init($url);
        // curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		// curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);

        // Decodes the JSON object into a PHP array
        $response1 = "[".$response."]";
        $dataset = json_decode($response1,true);
        echo "</br>"."################ ITER 0 #################"."</br>";
        var_dump($dataset);
        $dataset2 = "[".$dataset."]";
        $dataset3 = json_decode($dataset2, true);
        echo $dataset2;
        echo "</br>"."################ ITER 0 #################"."</br>";
        foreach($dataset3 as $elem) {
            $status = $elem['status'];
            echo $status;
            
            foreach ($elem['result'] as $result){
                $longitude = $result['longitude'];
                $latitude = $result['latitude'];
                $country = $result['country'];
                $parish = $result['parish'];

                echo $country." ".$parish." ".$longitude.' ',$latitude;
            }
        }
    }

?>