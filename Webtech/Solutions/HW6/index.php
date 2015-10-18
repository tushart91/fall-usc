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
    <script type="text/javascript">
        function resetForm()
        {
            document.getElementById("result").style.display = 'none';
        }
        function validateForm()
        {
            var address = document.getElementsByName("address")[0].value;
            var city = document.getElementsByName("city")[0].value;
            var state = document.getElementsByName("state")[0].value;
            if (!address)
            {
                alert("Enter Street Address");
                return false;
            }
            else if (!city)
            {
                alert("Enter City");
                return false;
            }
            else if (!state)
            {
                alert("Enter State");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="content">
            <h1 style="text-align: center">Forecast Search</h1>
            <form autocomplete="on" onreset="resetForm()" onsubmit="return validateForm()" method="post" >
                <table>
                    <tr>
                        <td><label for="address">Street Address *</label></td>
                        <td><input name="address" type="address" value="" /></td>
                    </tr>
                    <tr>
                        <td><label for="city">City *</label></td>
                        <td><input name="city" type="text" value="" /></td>
                    </tr>
                    <tr>
                        <td><label for="state">State *</label></td>
                        <td><select name="state">
                                <option value=""  >Select your state...</option>
                                <option value="AL">Alabama</option>
                                <option value="AK">Alaska</option>
                                <option value="AZ">Arizona</option>
                                <option value="AR">Arkansas</option>
                                <option value="CA">California</option>
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
                            <input type="radio" checked name="unit" id="us" value="us">
                            Fahrenheit
                            <input type="radio" name="unit" id="si" value="si">
                            Celsius
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
                        <div id="result">
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
                        </div>
                    <?php
                        else:
                            echo ("<script>alert(\"Cannot fetch latitude and longitude for given address\");</script>");
                        endif; ?>
                <script type="application/javascript">
                <?php
                    $address = isset($_POST['address']) ? $_POST['address'] : '';
                    $city = isset($_POST['city']) ? $_POST['city'] : '';
                    $state = isset($_POST['state']) ? $_POST['state'] : '';
                    $unit = isset($_POST['unit']) ? $_POST['unit'] : 'us';
                ?>
                    
                    var dict = {};
                    dict[""] = 0;
                    dict["AL"] = 1;
                    dict["AK"] = 2;
                    dict["AZ"] = 3;
                    dict["AR"] = 4;
                    dict["CA"] = 5;
                    dict["CO"] = 6;
                    dict["CT"] = 7;
                    dict["DE"] = 8;
                    dict["DC"] = 9;
                    dict["FL"] = 10;
                    dict["GA"] = 11;
                    dict["HI"] = 12;
                    dict["ID"] = 13;
                    dict["IL"] = 14;
                    dict["IN"] = 15;
                    dict["IA"] = 16;
                    dict["KS"] = 17;
                    dict["KY"] = 18;
                    dict["LA"] = 19;
                    dict["ME"] = 20;
                    dict["MD"] = 21;
                    dict["MA"] = 22;
                    dict["MI"] = 23;
                    dict["MN"] = 24;
                    dict["MS"] = 25;
                    dict["MO"] = 26;
                    dict["MT"] = 27;
                    dict["NE"] = 28;
                    dict["NV"] = 29;
                    dict["NH"] = 30;
                    dict["NJ"] = 31;
                    dict["NM"] = 32;
                    dict["NY"] = 33;
                    dict["NC"] = 34;
                    dict["ND"] = 35;
                    dict["OH"] = 36;
                    dict["OK"] = 37;
                    dict["OR"] = 38;
                    dict["PA"] = 39;
                    dict["RI"] = 40;
                    dict["SC"] = 41;
                    dict["SD"] = 42;
                    dict["TN"] = 43;
                    dict["TX"] = 44;
                    dict["UT"] = 45;
                    dict["VT"] = 46;
                    dict["VA"] = 47;
                    dict["WA"] = 48;
                    dict["WV"] = 49;
                    dict["WI"] = 50;
                    dict["WY"] = 51;
                    document.getElementsByName("address")[0].value = "<?php echo($address); ?>";
                    document.getElementsByName("city")[0].value = "<?php echo($city); ?>";
                    document.getElementsByName("state")[0].selectedIndex = dict["<?php echo($state); ?>"];
                    document.getElementById("<?php echo($unit); ?>").checked = true;
            </script>
            <?php endif; ?>
        </div>
    </div>
    
</body>
</html>
    