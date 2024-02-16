<?php

function getLocationName($filename, $locationID)
{
    $locationName = '';

    $jsonData = file_get_contents("assets/json/$filename");
    if ($jsonData) {
        $data = json_decode($jsonData, true);
        if (isset($data['data'])) {
            foreach ($data['data'] as $locationData) {
                if (strcmp($locationData['id'], $locationID) == 0) {
                    $locationName = $locationData['name'];
                    break; // Exit the loop since we found the matching ID
                }
            }
        }
    }

    return $locationName;
}
