var tabs = ['rightnow', 'nexttwentyfour', 'nextseven'],
    input = {
        address: 'address_error',
        city   : 'city_error',
        state  : 'state_error',
        unit   : null
    },
    result_div = "result",
    state_elem = "state",
    image_path = "images/",
    map = {
        unit: {
            us: {
                temperature: 'F',
                windspeed: 'mph',
                dewpoint: 'F',
                visibility: 'mi',
                pressure: 'mb'
            },
            si: {
                temperature: 'C',
                windspeed: 'm/s',
                dewpoint: 'C',
                visibility: 'km',
                pressure: 'hPa'
            }
        },
        image: {
            'clear-day': 'clear',
            'clear-night': 'clear_night',
            'rain': 'rain',
            'snow': 'snow',
            'sleet': 'sleet',
            'wind': 'wind',
            'fog': 'fog',
            'cloudy': 'cloudy',
            'partly-cloudy-day': 'cloud_day',
            'partly-cloudy-night': 'cloud_night'
        },
        precipitation: {
            label: {
                0: 'Heavy',
                1: 'Moderate',
                2: 'Light',
                3: 'Very Light',
                4: 'None'
            },
            values: {
                0: 0.4,
                1: 0.1,
                2: 0.017,
                3: 0.002,
                4: 0
            },
            length: 5
        }
    };
        
function resetForm() {
    "use strict";
    document.getElementsByName("address")[0].value = "";
    document.getElementsByName("city")[0].value = "";
    document.getElementsByName("state")[0].selectedIndex = 0;
    document.getElementById("us").checked = true;
    document.getElementById("address_error").style.display = "none";
    document.getElementById("city_error").style.display = "none";
    document.getElementById("state_error").style.display = "none";
    if (document.getElementById(result_div)) {
        document.getElementById(result_div).style.display = 'none';
    }
}
function validateForm() {
    "use strict";
    var key, value, flag = false;
    for (key in input) {
        if (input.hasOwnProperty(key) && input[key]) {
            value = document.getElementsByName(key)[0].value;
            if (!value) {
                document.getElementById(input[key]).style.display = "block";
                flag = true;
            } else {
                document.getElementById(input[key]).style.display = "none";
            }
        }
    }
    if (flag) {
        return false;
    }
    return true;
}
function resetError(sender, error) {
    "use strict";
    if (!sender.value && document.getElementById(error).style.display === "none") {
        document.getElementById(error).style.display = "block";
    }
}
function form_params() {
    "use strict";
    var key, params = "", value, partial;
    for (key in input) {
        if (input.hasOwnProperty(key)) {
            value = document.getElementsByName(key)[0].value;
            if (params !== "") {
                params += "&";
            }
            params += key + "=" + value;
        }
    }
    return params;
}
function getHours(hours) {
    "use strict";
    return (hours <= 12) ? hours : hours - 12;
}
function getMeridiem(hours) {
    "use strict";
    return (hours < 12) ? "AM" : "PM";
}
function convertTime(inputFormat) {
    "use strict";
    var utcSeconds, d;
    function pad(value) {
        return (value < 10) ? "0" + value : value;
    }
    utcSeconds = inputFormat;
    d = new Date(0); // The 0 there is the key, which sets the date to the epoch
    d.setUTCSeconds(utcSeconds);
    return [pad(getHours(d.getHours())), pad(d.getMinutes())].join(':') + " " +
        getMeridiem(d.getHours());
}
function magic(data) {
    "use strict";
    var i;
    document.getElementById('rightnow-weather').innerText = data.currently.summary;
    document.getElementById('rightnow-img').src =
        image_path + map.image[data.currently.icon] + ".png";
    document.getElementById('rightnow-img').title = data.currently.summary;
    document.getElementById('rightnow-img').alt = data.currently.summary;
    document.getElementById('rightnow-city').innerText = data.city;
    document.getElementById('rightnow-state').innerText = data.state;
    document.getElementById('rightnow-temp').innerText =
        Math.round(data.currently.temperature);
    document.getElementById('rightnow-unit').innerText = map.unit[data.unit].temperature;
    document.getElementById('rightnow-temp_low').innerText =
        Math.round(data.daily.data[0].temperatureMin);
    document.getElementById('rightnow-temp_high').innerText =
        Math.round(data.daily.data[0].temperatureMax);
    for (i = 0; i < map.precipitation.length; i += 1) {
        if (data.currently.precipIntensity >= map.precipitation.values[i]) {
            document.getElementById('rightnow-precipitation').innerText =
                map.precipitation.label[i];
            break;
        }
    }
    document.getElementById('rightnow-rain').innerText =
        String(Math.round(data.currently.precipProbability * 100));
    document.getElementById('rightnow-wind').innerText =
        String(Math.round(data.currently.windSpeed * 100) / 100) + " " +
        map.unit[data.unit].windspeed;
    document.getElementById('rightnow-dew-temp').innerText =
        String(Math.round(data.currently.dewPoint * 100) / 100);
    document.getElementById('rightnow-dew-unit').innerText =
        String(map.unit[data.unit].temperature);
    document.getElementById('rightnow-humidity').innerText =
        String(Math.round(data.currently.humidity));
    document.getElementById('rightnow-visibility').innerText =
        String(Math.round(data.currently.visibility * 100) / 100) + " " +
        map.unit[data.unit].visibility;
    document.getElementById('rightnow-sunrise').innerText =
        convertTime(data.daily.data[0].sunriseTime);
    document.getElementById('rightnow-sunset').innerText =
        convertTime(data.daily.data[0].sunsetTime);
    document.getElementById('result').style.display = 'block';
}
function submitForm() {
    "use strict";
//    if (!validateForm()) {
//        return;
//    }
    var params = form_params(),
        xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            magic(JSON.parse(xhttp.responseText));
        }
    };
    xhttp.open("POST", "index.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(params);
}
