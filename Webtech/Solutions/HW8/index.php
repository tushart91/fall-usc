<?php
if (!isset($_POST)) {
    $_POST = array();
}
$_POST['address'] = '1282 W 29th St';
$_POST['city']    = 'Los Angeles';
$_POST['state']   = 'CA';
$_POST['unit']    = 'us';
if (isset($_POST)) {
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $state = isset($_POST['state']) ? $_POST['state'] : '';
    $unit = isset($_POST['unit']) ? $_POST['unit'] : 'us';
    
    $map_url  = "http://maps.google.com/maps/api/geocode/xml?address=";
    $map_url .= $_POST["address"] . ",";
    $map_url .= $_POST["city"] . "," . $_POST["state"];
    $response_xml_data = simplexml_load_file($map_url);
    if (strcmp((string)$response_xml_data->status,"OK") == 0) {
        $lat = (string)$response_xml_data->result->geometry->location->lat;
        $lng = (string)$response_xml_data->result->geometry->location->lng;
        $api = "https://api.forecast.io/forecast/37bf1528687d1cc9ccb05eb372a2f442/";
        $api .= $lat.",".$lng."?units=".$_POST["unit"]."&exclude=flags";
        $json = json_decode(file_get_contents($api), true);
        unset($json['minutely']);
        $json['address'] = $_POST['address'];
        $json['city'] = $_POST['city'];
        $json['state'] = $_POST['state'];
        $json['unit'] = $_POST['unit'];
        header('Content-Type: application/json');
        echo(json_encode($json));
    }
}
?>