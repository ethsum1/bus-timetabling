<?php
    include_once 'header.php';

    echo "<button class='btn btn-secondary my-2' onclick='backBtn();'>Go back</button>";

    if(isset($_COOKIE["stop_id"])){

        $sql = "SELECT * FROM bus_stops WHERE stop_id=".$_COOKIE['stop_id'].";";
        require_once 'backend/dbconfig.php';
        $result = mysqli_query($conn,$sql);

        while($row = mysqli_fetch_array($result)) {
            $stop_id = $_COOKIE["stop_id"];
            $atco_code = $row["atco_code"];
            $naptan_code = $row["naptan_code"];
            $name = $row["name"];
            $street = $row["street"];
            $town = $row["town"];
            $indicator = $row["indicator"];
        }

        echo "<h1>$name ($indicator)</h1>";
        echo "<p>$street, $town</p>";


        // Defines enpoint and user/pwd data for API
        $API_ENDPOINT = "http://nextbus.mxdata.co.uk/nextbuses/1.0/1";
        $API_USER = "TravelineAPI608";
        $API_PASSWORD = "2Fwu69omxoHM";


        function createXMLRequest($naptan) {
            global $API_USER;
            $request_timestamp = date("Y-m-d\TH:i:s");
            $message_ref = "";

            // Creates unique message reference ID
            for ($i = 0; $i < 10; $i++) {
                $message_ref .= rand(0, 9);
            }
            
            // Creates XML request data tree structure
            // Uses object oriented approach
            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Siri version="1.0" xmlns="http://www.siri.org.uk/"></Siri>');
            $sr = $xml->addChild('ServiceRequest');
            $sr->addChild('RequestTimestamp', $request_timestamp);
            $sr->addChild('RequestorRef', $API_USER);
            $smr = $sr->addChild('StopMonitoringRequest', 'version="1.0"');
            $smr->addChild('RequestTimestamp', $request_timestamp);
            $smr->addChild('MessageIdentifier', $message_ref);
            $smr->addChild('MonitoringRef', $naptan);

            return $xml->asXML();
        }

        $xmlstr = createXMLRequest($naptan_code);

        // Uses cURL library to set options for POST request
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $API_ENDPOINT); // Sets API URL
        curl_setopt($curl, CURLOPT_POST, true); // Sets as a POST request
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlstr); // Adds the data tree to the request
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Allows a response
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "$API_USER:$API_PASSWORD"); // Sets authentication credentials
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml")); // Allows for XML data

        $response = curl_exec($curl);
        curl_close($curl);

        // Recieves XML document as response and converts to JSON file
        // Converts JSON file to array
        $xml_response = simplexml_load_string($response);
        $json_response = json_encode($xml_response);  
        $arr_response = json_decode($json_response, true);
        $services = $arr_response["ServiceDelivery"]["StopMonitoringDelivery"]["MonitoredStopVisit"];


        // Formats date in hours and mins only
        // Uses object oriented approach
        function formatDateTime($datetime) {
            $date = new DateTime($datetime);
            return $date->format('H:i');
        }

        $count = 0;
        if (!empty($services)) {
            echo "<table>";
            echo "<tr><th>Line Name</th><th>Departure Time</th><th>Direction</th></tr>";

            foreach ($services as $service) {
                // Limits to 5 bus stops
                if ($count >= 5) {
                    break;
                }
                $line_name = $service["MonitoredVehicleJourney"]["PublishedLineName"];
                $departure_time = $service["MonitoredVehicleJourney"]["MonitoredCall"]["AimedDepartureTime"];
                $direction = $service["MonitoredVehicleJourney"]["DirectionName"];

                // Displays row for each piece of data
                echo "<tr><td>$line_name</td><td>".formatDateTime($departure_time)."</td><td>$direction</td></tr>";
                $count++;
            }
            echo "</table>";
        } else {
            // Error handling
            echo '<div class="alert alert-danger" role="alert">There were no services found for this bus stop. Please try again with another bus stop.</div>';
        }

        

    }

?>