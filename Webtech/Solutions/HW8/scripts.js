var input = {
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
