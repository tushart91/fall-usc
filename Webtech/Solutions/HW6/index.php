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
    
    <div class="container">
        <div class="content">
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST'):
                    date_default_timezone_set('America/Los_Angeles');
                    $map = array();
                    $map['units'] = array();
                    $map['units']['us'] = "F";
                    $map['units']['si'] = "C";
                    $map['image'] = array();
                    $map['image']['clear-day'] = "clear";
                    $map['image']['clear-night'] = "clear-night";
                    $map['image']['rain'] = "rain";
                    $map['image']['snow'] = "snow";
                    $map['image']['sleet'] = "sleet";
                    $map['image']['wind'] = "clear";
                    $map['image']['fog'] = "wind";
                    $map['image']['cloudy'] = "cloudy";
                    $map['image']['partly-cloudy-day'] = "cloud_day";
                    $map['image']['partly-cloudy-night'] = "cloud_night";
                    $map['precipitation'] = array();
                    $map['precipitation'][0] = "None";
                    $map['precipitation'][0.002] = "Very Light";
                    $map['precipitation'][0.017] = "Light";
                    $map['precipitation'][0.01] = "Moderate";
                    $map['precipitation'][0.04] = "Heavy";
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
                    <?php endif;
                else:
            ?>
            <h1 style="text-align: center">Forecast Search</h1>
            <form method="post" >
                <table>
                    <tr>
                        <td><label for="address">Street Address *</label></td>
                        <td><input name="address" type="address" value="1282 W 29th St" required /></td>
                    </tr>
                    <tr>
                        <td><label for="city">City *</label></td>
                        <td><input name="city" type="text" value="Los Angeles" required /></td>
                    </tr>
                    <tr>
                        <td><label for="state">State *</label></td>
                        <td><select name="state" required>
                                <option value="CA">California</option>
                                <option value=""  >Select your state...</option>
                                <option value="AL">Alabama</option>
                                <option value="AK">Alaska</option>
                                <option value="AZ">Arizona</option>
                                <option value="AR">Arkansas</option>
                                <option value="CO">Colorado</option>
                                <option value="CT">Connecticut</option>
                                <option value="DE">Delaware</option>
                                <option value="DC">District Of Columbia</option>
                                <option value="FL">Florida</option>
                                <option value="GA">Georgia</option>
                                <option value="HI">Hawaii</option>
                                <option value="ID">Idaho</option>
                                <option value="IL">Illinois</option>
                                <option value="IN">Indiana</option>
                                <option value="IA">Iowa</option>
                                <option value="KS">Kansas</option>
                                <option value="KY">Kentucky</option>
                                <option value="LA">Louisiana</option>
                                <option value="ME">Maine</option>
                                <option value="MD">Maryland</option>
                                <option value="MA">Massachusetts</option>
                                <option value="MI">Michigan</option>
                                <option value="MN">Minnesota</option>
                                <option value="MS">Mississippi</option>
                                <option value="MO">Missouri</option>
                                <option value="MT">Montana</option>
                                <option value="NE">Nebraska</option>
                                <option value="NV">Nevada</option>
                                <option value="NH">New Hampshire</option>
                                <option value="NJ">New Jersey</option>
                                <option value="NM">New Mexico</option>
                                <option value="NY">New York</option>
                                <option value="NC">North Carolina</option>
                                <option value="ND">North Dakota</option>
                                <option value="OH">Ohio</option>
                                <option value="OK">Oklahoma</option>
                                <option value="OR">Oregon</option>
                                <option value="PA">Pennsylvania</option>
                                <option value="RI">Rhode Island</option>
                                <option value="SC">South Carolina</option>
                                <option value="SD">South Dakota</option>
                                <option value="TN">Tennessee</option>
                                <option value="TX">Texas</option>
                                <option value="UT">Utah</option>
                                <option value="VT">Vermont</option>
                                <option value="VA">Virginia</option>
                                <option value="WA">Washington</option>
                                <option value="WV">West Virginia</option>
                                <option value="WI">Wisconsin</option>
                                <option value="WY">Wyoming</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Degree *</td>
                        <td>
                            <input type="radio" checked required name="unit" value="us">Fahrenheit
                            <input type="radio" required name="unit" value="si">Celsius
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
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
    