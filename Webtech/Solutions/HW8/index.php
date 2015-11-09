<?php
if (isset($_GET)) {
    $address = $_GET['address'] or die('Address missing');
    $city = $_GET['city'] or die ('city missing');
    $state = $_GET['state'] or die('state missing');
    $unit = $_GET['unit'] or die('unit missing');
    
    $map_url  = "http://maps.google.com/maps/api/geocode/xml?address=";
    $map_url .= $_GET["address"] . ",";
    $map_url .= $_GET["city"] . "," . $_GET["state"];
    $response_xml_data = simplexml_load_file($map_url);
    if (strcmp((string)$response_xml_data->status,"OK") == 0) {
        $lat = (string)$response_xml_data->result->geometry->location->lat;
        $lng = (string)$response_xml_data->result->geometry->location->lng;
        $api = "https://api.forecast.io/forecast/37bf1528687d1cc9ccb05eb372a2f442/";
        $api .= $lat.",".$lng."?units=".$_GET["unit"]."&exclude=flags";
        $json = json_decode(file_get_contents($api), true);
        unset($json['minutely']);
        $json['address'] = $_GET['address'];
        $json['city'] = $_GET['city'];
        $json['state'] = $_GET['state'];
        $json['unit'] = $_GET['unit'];
        header('Content-Type: application/json');
        echo(json_encode($json));
    }
}
?>
