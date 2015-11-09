var input = {
        address: 'address_error',
        city   : 'city_error',
        state  : 'state_error'
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
    },
    imap = null;
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
    params += "&unit=" + $('input[name="unit"]:checked').val();

    return params;
}
function submitForm() {
    "use strict";
    resetOutput();
    var params = form_params(),
        xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            magic(JSON.parse(xhttp.responseText));
        }
    };
    xhttp.open("GET", "index.php?" + params, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
}

$(document).ready(function() {
    $('form').validate({
        submitHandler: function(form) {
            submitForm();
            return false;
        },
        rules: {
            address: {
                required: {
                    depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                }
            },
            city: {
                required: {
                    depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                }
            },
            state: {
                required: {
                    depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                }
            },
            unit: {
                required: true
            }
        },
        messages: {
            address: "Please enter the street address",
            city: "Please enter the city",
            state: "Please select a state"
        },
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        errorElement: 'div',
        errorClass: 'error',
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }
    });
});

function resetOutput() {
    if (imap)
    {
        imap.destroy();
        $(".tab-button:first").click();
    }
}

function resetForm() {
    "use strict";
    $('form').validate().resetForm();
    document.getElementsByName("address")[0].value = "";
    document.getElementsByName("city")[0].value = "";
    document.getElementsByName("state")[0].selectedIndex = 0;
    document.getElementById("us").checked = true;
    resetOutput();
    if (document.getElementById(result_div)) {
        document.getElementById(result_div).style.display = 'none';
    }
}
