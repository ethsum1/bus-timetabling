<?php
    include_once 'header.php';

    if(isset($_COOKIE["stop_id"])){

        $sql = "SELECT * FROM bus_stops WHERE stop_id=".$_COOKIE['stop_id'].";";
        require_once 'includes/dbconfig.inc.php';
        $result = mysqli_query($conn,$sql);

        while($row = mysqli_fetch_array($result)) {
            $stop_id = $_COOKIE["stop_id"];
            $atco_code = $row["atco_code"];
            $naptan_code = $row["naptan_code"];
            $name = $row["name"];
            $street = $row["street"];
            $town = $row["town"];
        }

        echo "<h1>".$name."</h1>";
        echo "<p>".$street." - ".$town."</p>";


        // Bus timetable data
        $API_ENDPOINT = "http://nextbus.mxdata.co.uk/nextbuses/1.0/1";
        $API_USER = "TravelineAPI608";
        $API_PASSWORD = "2Fwu69omxoHM";

        function createXMLBody($naptan) {
            global $API_USER;
            $request_timestamp = date("Y-m-d\TH:i:s");
            $message_ref = "";
            for ($i = 0; $i < 10; $i++) {
                $message_ref .= rand(0, 9);
            }
            
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



        $xmlstr = createXMLBody($naptan_code);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $API_ENDPOINT);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlstr);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "$API_USER:$API_PASSWORD");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));

        $response = curl_exec($curl);
        curl_close($curl);

        $xml = simplexml_load_string($response);
        $json = json_encode($xml);  


        function formatDateTime($datetime) {
            $date = new DateTime($datetime);
            return $date->format('H:i');
        }


        $data = json_decode($json, true);
        $count = 0;
        $services = $data["ServiceDelivery"]["StopMonitoringDelivery"]["MonitoredStopVisit"];

        echo "<table>";
        echo "<tr><th>Line Name</th><th>Departure Time</th><th>Direction</th></tr>";

        foreach ($services as $service) {
            if ($count >= 5) {
                break;
            }

            $lineName = $service["MonitoredVehicleJourney"]["PublishedLineName"];
            $departureTime = $service["MonitoredVehicleJourney"]["MonitoredCall"]["AimedDepartureTime"];
            $direction = $service["MonitoredVehicleJourney"]["DirectionName"];

            echo "<tr><td>$lineName</td><td>".formatDateTime($departureTime)."</td><td>$direction</td></tr>";

            $count++;
        }

        echo "</table>";

    }

?>