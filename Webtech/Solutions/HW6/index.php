<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Forecast</title>
    <meta name="description" content="Forecast">
    <meta name="author" content="Tushar Tiwari">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: table;
        }
        .container {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }
        .content {
            display: inline-block;
            text-align: left;
            border-color: black;
            border-width: medium;
            border-style: inset;
            padding: 10px 40px;
        }
        td {
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <?php
        $address = isset($_POST['address']) ? $_POST['address'] : '';
        $city = isset($_POST['city']) ? $_POST['city'] : '';
        $state = isset($_POST['state']) ? $_POST['state'] : '';
        $unit = isset($_POST['unit']) ? $_POST['unit'] : 'us';
    ?>
    <div class="container">
        <div class="content">
            <h1 style="text-align: center">Forecast Search</h1>
            <form autocomplete="on" method="post" >
                <table>
                    <tr>
                        <td><label for="address">Street Address *</label></td>
                        <td><input name="address" type="address" value="<?php echo $address; ?>" required /></td>
                    </tr>
                    <tr>
                        <td><label for="city">City *</label></td>
                        <td><input name="city" type="text" value="<?php echo $city; ?>" required /></td>
                    </tr>
                    <tr>
                        <td><label for="state">State *</label></td>
                        <td><select name="state" required>
                            <?php foreach(array(
                                "" => 'Select your state...',
                                "CA" => 'California',
                                "AL" => 'Alabama',
                                "AK" => 'Alaska',
                                "AZ" => 'Arizona',
                                "AR" => 'Arkansas',
                                "CO" => 'Colorado',
                                "CT" => 'Connecticut',
                                "DE" => 'Delaware',
                                "DC" => 'District Of Columbia',
                                "FL" => 'Florida',
                                "GA" => 'Georgia',
                                "HI" => 'Hawaii',
                                "ID" => 'Idaho',
                                "IL" => 'Illinois',
                                "IN" => 'Indiana',
                                "IA" => 'Iowa',
                                "KS" => 'Kansas',
                                "KY" => 'Kentucky',
                                "LA" => 'Louisiana',
                                "ME" => 'Maine',
                                "MD" => 'Maryland',
                                "MA" => 'Massachusetts',
                                "MI" => 'Michigan',
                                "MN" => 'Minnesota',
                                "MS" => 'Mississippi',
                                "MO" => 'Missouri',
                                "MT" => 'Montana',
                                "NE" => 'Nebraska',
                                "NV" => 'Nevada',
                                "NH" => 'New Hampshire',
                                "NJ" => 'New Jersey',
                                "NM" => 'New Mexico',
                                "NY" => 'New York',
                                "NC" => 'North Carolina',
                                "ND" => 'North Dakota',
                                "OH" => 'Ohio',
                                "OK" => 'Oklahoma',
                                "OR" => 'Oregon',
                                "PA" => 'Pennsylvania',
                                "RI" => 'Rhode Island',
                                "SC" => 'South Carolina',
                                "SD" => 'South Dakota',
                                "TN" => 'Tennessee',
                                "TX" => 'Texas',
                                "UT" => 'Utah',
                                "VT" => 'Vermont',
                                "VA" => 'Virginia',
                                "WA" => 'Washington',
                                "WV" => 'West Virginia',
                                "WI" => 'Wisconsin',
                                "WY" => 'Wyoming'
                            ) as $key => $val){
                                ?><option value="<?php echo $key; ?>"<?php
                                    if($key==$state) echo ' selected="selected"';
                                ?>><?php echo $val; ?></option><?php
                            }?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Degree *</td>
                        <td>
                            <?php foreach(array(
                                "us" => 'Farenheit',
                                "si" => 'Celsius'
                            ) as $key => $val){
                                ?><input type="radio" required name="unit" 
                                         value="<?php echo $key; ?>"
                                         <?php if($key==$unit) echo ' checked';?> />
                                         <?php echo $val; ?><?php } ?>
                        </td>
                    </tr>
                </table><br />
                <div style="text-align: center">
                    <input type="submit" value="Submit" />
                    <input type="reset" value="Clear" />
                </div>
            </form><br />
            <div>* - <i>Mandatory fields.</i></div><br />
            <div style="text-align: center"><a href="http://forecast.io">Powered by Forecast.io</a></div>
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST'):
                    $map = array();
                    $map['units'] = array();
                    $map['units']['us'] = "F";
                    $map['units']['si'] = "C";
                    $map['image'] = array();
                    $map['image']['clear-day'] = "clear";
                    $map['image']['clear-night'] = "clear_night";
                    $map['image']['rain'] = "rain";
                    $map['image']['snow'] = "snow";
                    $map['image']['sleet'] = "sleet";
                    $map['image']['wind'] = "wind";
                    $map['image']['fog'] = "fog";
                    $map['image']['cloudy'] = "cloudy";
                    $map['image']['partly-cloudy-day'] = "cloud_day";
                    $map['image']['partly-cloudy-night'] = "cloud_night";
                    $map['precipitation'] = array();
                    $map['precipitation'][0] = "None";
                    $map['precipitation'][0.002] = "Very Light";
                    $map['precipitation'][0.017] = "Light";
                    $map['precipitation'][0.1] = "Moderate";
                    $map['precipitation'][0.4] = "Heavy";
                    $map_url  = "http://maps.google.com/maps/api/geocode/xml?address=";
                    $map_url .= $_POST["address"] . ",";
                    $map_url .= $_POST["city"] . "," . $_POST["state"];
                    $response_xml_data = simplexml_load_file($map_url);
                    if (strcmp((string)$response_xml_data->status,"OK") == 0):
                        $lat = (string)$response_xml_data->result->geometry->location->lat;
                        $lng = (string)$response_xml_data->result->geometry->location->lng;
                        $api = "https://api.forecast.io/forecast/37bf1528687d1cc9ccb05eb372a2f442/";
                        $api .= $lat.",".$lng."?units=".$_POST["unit"]."&exclude=flags";
                        $json = json_decode(file_get_contents($api), true);?>
                        <br />
                        <hr>
                        <h1 style="text-align: center"><?php echo ($json['currently']['summary']); ?></h1>
                        <h1 style="text-align: center"><?php echo ($json['currently']['temperature']); ?>&deg; <?php echo ($map['units'][$_POST["unit"]]); ?></h1>
                        <div style="text-align: center">
                            <img src="images/<?php echo($map['image'][$json['currently']['icon']]) ?>.png" alt-text="<?php echo($json['currently']['summary']); ?>"/></div>
                        <table>
                            <tr>
                                <td>Precipitation</td>
                                <td><?php echo($map['precipitation'][$json['currently']['precipIntensity']]); ?></td>
                            </tr>
                            <tr>
                                <td>Chance of Rain</td>
                                <td><?php echo($json['currently']['precipProbability'] * 100)."%"; ?></td>
                            </tr>
                            <tr>
                                <td>Wind Speed</td>
                                <td><?php echo(intval($json['currently']['windSpeed']))." mph"; ?></td>
                            </tr>
                            <tr>
                                <td>Dew Point</td>
                                <td><?php echo(intval($json['currently']['dewPoint'])); ?></td>
                            </tr>
                            <tr>
                                <td>Humidity</td>
                                <td><?php echo($json['currently']['humidity']* 100)."%"; ?></td>
                            </tr>
                            <tr>
                                <td>Visibility</td>
                                <td><?php echo(intval($json['currently']['visibility']))." mi"; ?></td>
                            </tr>
                            <tr>
                                <td>Sunrise</td>
                                <td><?php echo(date('h:i A', $json['daily']['data'][0]["sunriseTime"])); ?></td>
                            </tr>
                            <tr>
                                <td>Sunset</td>
                                <td><?php echo(date('h:i A', $json['daily']['data'][0]["sunsetTime"])); ?></td>
                            </tr>
                        </table>
                    <?php
                        else:
                            echo ("<script>alert(\"Cannot fetch latitude and longitude for given address\"</script>");
                        endif;
                endif;
            ?>
        </div>
    </div>
</body>
</html>
    