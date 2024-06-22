<?php
$routestartlat = 38.31644984348378;
$routestartlng = 26.304144747627962;
$routeendlat = 41.396841614426755;
$routeendlng = 41.421473982637416;
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://routes.googleapis.com/directions/v2:computeRoutes',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
  "origin":{
    "location":{
      "latLng":{
        "latitude": ' . $routestartlat . ', 
        "longitude":  ' . $routestartlng . '
      }
    }
  },
  "destination":{
    "location":{
      "latLng":{
        "latitude":  ' . $routeendlat . ', 
        "longitude": ' . $routeendlng . '
      }
    }
  },
  "travelMode": "DRIVE",
  "routingPreference": "TRAFFIC_AWARE",
  "computeAlternativeRoutes": true,
  "languageCode": "en-US",
  "units": "METRIC"
}',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'X-Goog-Api-Key: AIzaSyC_CZhA-m9tokKC1JCt5b4pY_dt8MzfvTM',
        'X-Goog-FieldMask: *'
    ),
));
$response = json_decode(curl_exec($curl), true);
curl_close($curl);
echo '<table>';
echo '<tr>
<th>Route</th>
            <th>Distance (meters)</th>
            <th>Duration (seconds)</th>
            <th>Start Location (Latitude)</th>
            <th>Start Location (Longitude)</th>
            <th>Start Location (Evelation)</th>
            <th>End Location (Latitude)</th>
            <th>End Location (Longitude)</th>
            <th>End Location (Evelation)</th>
            <th>Vertical Change</th>
            <th>Horizontal Duration</th>
            <th>Slope (%)</th>
            <th>Navigation Instruction</th>
          </tr>';
$coordinates = array();
foreach ($response['routes'] as $index => $route) {
    foreach ($route['legs'] as $leg) {
        foreach ($leg['steps'] as $step) {
            $curl2 = curl_init();
            curl_setopt_array($curl2, array(
                CURLOPT_URL => 'https://maps.googleapis.com/maps/api/elevation/json?locations=' . $step['startLocation']['latLng']['latitude'] . '%2C' . $step['startLocation']['latLng']['longitude'] . '%7C' . $step['endLocation']['latLng']['latitude'] . '%2C' . $step['endLocation']['latLng']['longitude'] . '&key=AIzaSyC_CZhA-m9tokKC1JCt5b4pY_dt8MzfvTM',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $elevations = json_decode(curl_exec($curl2), true);
            curl_close($curl2);
            $verticalChange = $elevations['results'][0]['elevation'] - $elevations['results'][1]['elevation'];
            $horizontalDistance = sqrt(pow($step['distanceMeters'], 2) - pow($verticalChange, 2));
            $slope = $verticalChange / $horizontalDistance * 100;
            $color = '';
            $a = false;
            $b = false;
            if ($index == 0) {
                foreach ($response['routes'][1]['legs'][0]['steps'] as $step2) {
                    if ($step['startLocation']['latLng'] == $step2['startLocation']['latLng']) {
                        $a = true;
                        break;
                    }
                }
                foreach ($response['routes'][2]['legs'][0]['steps'] as $step2) {
                    if ($step['startLocation']['latLng'] == $step2['startLocation']['latLng']) {
                        $b = true;
                        break;
                    }
                }
                if ($a == true && $b == true) {
                    $color = '#FFA500';
                    $c = "1-2-3";
                } else if ($a == true && $b == false) {
                    $color = '#FFFF00';
                    $c = "1-2";
                } else  if ($a == false && $b == true) {
                    $color = '#FF00FF';
                    $c = "1-3";
                } else {
                    $color = '#FF0000';
                    $c = "1";
                }
            } else if ($index == 1) {
                foreach ($response['routes'][0]['legs'][0]['steps'] as $step2) {
                    if ($step['startLocation']['latLng'] == $step2['startLocation']['latLng']) {
                        $a = true;
                        break;
                    }
                }
                foreach ($response['routes'][2]['legs'][0]['steps'] as $step2) {
                    if ($step['startLocation']['latLng'] == $step2['startLocation']['latLng']) {
                        $b = true;
                        break;
                    }
                }
                if ($a == true && $b == true) {
                    $color = '#FFA500';
                    $c = "1-2-3";
                } else if ($a == true && $b == false) {
                    $color = '#FFFF00';
                    $c = "1-2";
                } else  if ($a == false && $b == true) {
                    $color = '#00FFFF';
                    $c = "2-3";
                } else {
                    $color = '#00FF00';
                    $c = "2";
                }
            } else if ($index == 2) {
                foreach ($response['routes'][0]['legs'][0]['steps'] as $step2) {
                    if ($step['startLocation']['latLng'] == $step2['startLocation']['latLng']) {
                        $a = true;
                        break;
                    }
                }
                foreach ($response['routes'][1]['legs'][0]['steps'] as $step2) {
                    if ($step['startLocation']['latLng'] == $step2['startLocation']['latLng']) {
                        $b = true;
                        break;
                    }
                }
                if ($a == true && $b == true) {
                    $color = '#FFA500';
                    $c = "1-2-3";
                } else if ($a == true && $b == false) {
                    $color = '#FF00FF';
                    $c = "1-3";
                } else  if ($a == false && $b == true) {
                    $color = '#00FFFF';
                    $c = "2-3";
                } else {
                    $color = '#0000FF';
                    $c = "3";
                }
            }
            $coordinates[] = [
                'coordinates' => [
                    '' . strval($step['startLocation']['latLng']['longitude']) . '',
                    '' . strval($step['startLocation']['latLng']['latitude']) . ''
                ],
                'title' => '' . strval($c) . '',
                'color' => $color
            ];
            echo '<tr>';
            echo '<td>' . $index + 1 . '</td>';
            echo '<td>' . $step['distanceMeters'] . '</td>';
            echo '<td>' . $step['staticDuration'] . '</td>';
            echo '<td>' . round($step['startLocation']['latLng']['latitude'], 2) . '</td>';
            echo '<td>' . round($step['startLocation']['latLng']['longitude'], 2) . '</td>';
            echo '<td>' . round($elevations['results'][0]['elevation'], 2) . '</td>';
            echo '<td>' . round($step['endLocation']['latLng']['latitude'], 2) . '</td>';
            echo '<td>' . round($step['endLocation']['latLng']['longitude'], 2) . '</td>';
            echo '<td>' . round($elevations['results'][1]['elevation'], 2) . '</td>';
            echo '<td>' . round($verticalChange, 2) . '</td>';
            echo '<td>' . round($horizontalDistance, 2) . '</td>';
            echo '<td>' . round($slope, 2) . '</td>';
            echo '<td>';
            if (isset($step['navigationInstruction']['instructions'])) {
                echo $step['navigationInstruction']['instructions'];
            } else {
                echo 'No instructions available';
            }
            echo '</td>';
            echo '</tr>';
        }
    }
}
echo '</table>';
