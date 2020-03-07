<?php
require('vendor/autoload.php');
$response =  \GeometryLibrary\SphericalUtil::computeDistanceBetween(
    ['lat' => 0.412263, 'lng' => 9.459454], // from array [lat, lng]
    ['lat' => 0.391986, 'lng' => 9.452780]); // to array [lat, lng]
echo $response/1000; // -180
?>