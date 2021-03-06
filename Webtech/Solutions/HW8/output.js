var nextseven_colors = ['357cb4', 'eb4343', 'e58d4e', 'a7a439', '9770a7', 'f37c7e', 'ce4571'],
    weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    month = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

function convertTime(inputFormat, timezone) {
    "use strict";
    return moment.unix(inputFormat).tz(timezone).format('hh:mm A');
}
function die(input, unit) {
    if (input === undefined) {
        return "N.A."
    }
    return input + " " + unit;
}
function populate_rightnow(data) {
    "use strict";
    var i;
    document.getElementById('rightnow-weather').innerText =
    document.getElementById('rightnow-weather').textContent = data.currently.summary;
    document.getElementById('rightnow-img').src =
        image_path + map.image[data.currently.icon] + ".png";
    document.getElementById('rightnow-img').title = data.currently.summary;
    document.getElementById('rightnow-img').alt = data.currently.summary;
    document.getElementById('rightnow-city').innerText =
    document.getElementById('rightnow-city').textContent = data.$city;
    document.getElementById('rightnow-state').innerText =
    document.getElementById('rightnow-state').textContent = data.state;
    document.getElementById('rightnow-temp').innerText =
    document.getElementById('rightnow-temp').textContent =
        Math.round(data.currently.temperature);
    document.getElementById('rightnow-unit').innerText
    document.getElementById('rightnow-unit').textContent = map.unit[data.unit].temperature;
    document.getElementById('rightnow-temp_low').innerText =
    document.getElementById('rightnow-temp_low').textContent =
        Math.round(data.daily.data[0].temperatureMin);
    document.getElementById('rightnow-temp_high').innerText =
    document.getElementById('rightnow-temp_high').textContent =
        Math.round(data.daily.data[0].temperatureMax);
    for (i = 0; i < map.precipitation.length; i += 1) {
        if (data.currently.precipIntensity >= map.precipitation.values[i]) {
            document.getElementById('rightnow-precipitation').innerText =
            document.getElementById('rightnow-precipitation').textContent =
                map.precipitation.label[i];
            break;
        }
    }
    document.getElementById('rightnow-rain').innerText =
        document.getElementById('rightnow-rain').textContent =
        String(Math.round(data.currently.precipProbability * 100));
    document.getElementById('rightnow-wind').innerText =
        document.getElementById('rightnow-wind').textContent =
        String(Math.round(data.currently.windSpeed * 100) / 100) + " " +
        map.unit[data.unit].windspeed;
    document.getElementById('rightnow-dew-temp').innerText =
        document.getElementById('rightnow-dew-temp').textContent =
        String(Math.round(data.currently.dewPoint * 100) / 100);
    document.getElementById('rightnow-dew-unit').innerText =
        document.getElementById('rightnow-dew-unit').textContent =
        String(map.unit[data.unit].temperature);
    document.getElementById('rightnow-humidity').innerText =
        document.getElementById('rightnow-humidity').textContent =
        String(Math.round(data.currently.humidity));
    document.getElementById('rightnow-visibility').innerText =
        document.getElementById('rightnow-visibility').textContent =
        String(Math.round(data.currently.visibility * 100) / 100) + " " +
        map.unit[data.unit].visibility;
    document.getElementById('rightnow-sunrise').innerText =
        document.getElementById('rightnow-sunrise').textContent =
        convertTime(data.daily.data[0].sunriseTime, data.timezone);
    document.getElementById('rightnow-sunset').innerText =
        document.getElementById('rightnow-sunset').textContent =
        convertTime(data.daily.data[0].sunsetTime, data.timezone);
}
function populate_nextseven(data) {
    "use strict";
    var button_string = "", modal_string = "", i, j, d, dayta;
    for (i = 0; i < 7; i += 1) {
        if (i === 4) {
            button_string += '<div class="hidden-xs hidden-md visible-sm-block\
                                          col-sm-2 col-centered"></div>';
        }
        dayta = data.daily.data[i + 1];
        d = moment.unix(dayta.time).tz(data.timezone);
        button_string +=
            '<div class="col-xs-12 col-sm-2 col-md-1 col-centered">\
                 <button type="button" class="btn col-radius fill" data-toggle="modal"\
                         style="background: #' + nextseven_colors[i] +
                             '" data-target="#modal'+ String(i) +'">\
                     <div class="dlabel lab-pad">' + weekday[d.day()] + '</div>\
                     <div class="dlabel lab-pad">'+ month[d.month()] + ' ' +
                         d.date() + '</div>\
                     <div class="lab-pad">\
                         <img src="' + image_path + map.image[dayta.icon] + '.png" width="100px"\
                          alt="' + dayta.summary + '" title="' + dayta.summary + '"/>\
                     </div>\
                     <div class="white lab-pad">Min<br />Temp</div>\
                     <div class="white lab-pad temp">' + Math.round(dayta.temperatureMin) + '&deg;</div>\
                     <div class="white lab-pad">Max<br />Temp</div>\
                     <div class="white lab-pad temp">' + Math.round(dayta.temperatureMax) + '&deg;</div>\
                 </button>\
             </div>"';

        modal_string +=
            '<div id="modal'+ String(i) +'" class="modal fade" role="dialog">\
                 <div class="modal-dialog">\
                     <div class="modal-content" tabindex="-1">\
                         <div class="modal-header">\
                             <button type="button" class="close" data-dismiss="modal">&times</button>\
                             <h5 class="modal-title">Weather in ' + data.$city +' on ' +
                                 month[d.month()] + ' ' + d.date() + '</h5>\
                         </div>\
                         <div class="modal-body text-center">\
                             <div><img src="' + image_path + map.image[dayta.icon] + '.png" width="120px" /></div>\
                             <div style="padding: 30px 0 15px 0">\
                                 <span class="modal-day">' + weekday[d.day()] + ':</span>\
                                 <span class="modal-caption">' + dayta.summary + '</span>\
                             </div>\
                            <div class="row row-centered">\
                                 <div class="col-xs-7 col-sm-6 col-md-4 col-centered">\
                                     <h5 class="modal-table-header modal-title">Sunrise Time</h5>\
                                     <div class="modal-value">' +
                                         convertTime(dayta.sunriseTime, data.timezone) + '</div>\
                                 </div>\
                                 <div class="col-xs-7 col-sm-6 col-md-4 col-centered">\
                                     <h5 class="modal-table-header modal-title">Sunset Time</h5>\
                                     <div class="modal-value">' +
                                         convertTime(dayta.sunsetTime, data.timezone) + '</div>\
                                 </div>\
                                 <div class="col-xs-7 col-sm-6 col-md-4 col-centered">\
                                     <h5 class="modal-table-header modal-title">Humidity</h5>\
                                     <div class="modal-value">'+ die(Math.round(dayta.humidity), '%') +'</div>\
                                 </div>\
                                 <div class="col-xs-7 col-sm-6 col-md-4 col-centered">\
                                     <h5 class="modal-table-header modal-title">Windspeed</h5>\
                                     <div class="modal-value">' + die(dayta.windSpeed,
                                         map.unit[data.unit].windspeed) + '</div>\
                                 </div>\
                                 <div class="col-xs-7 col-sm-6 col-md-4 col-centered">\
                                     <h5 class="modal-table-header modal-title">Visibility</h5>\
                                     <div class="modal-value">' + die(dayta.visibility,
                                         map.unit[data.unit].visibility) + '</div>\
                                 </div>\
                                 <div class="col-xs-7 col-sm-6 col-md-4 col-centered">\
                                     <h5 class="modal-table-header modal-title">Pressure</h5>\
                                     <div class="modal-value">' + die(dayta.pressure,
                                         map.unit[data.unit].pressure) + '</div>\
                                 </div>\
                             </div>\
                         </div>\
                         <div class="modal-footer">\
                             <button type="button" class="btn btn-default" \
                                     data-dismiss="modal">Close</button>\
                         </div>\
                     </div>\
                 </div>\
                 </div>';
    }
    document.getElementById('nextseven-container').innerHTML = button_string;
    document.getElementById('modal-container').innerHTML = modal_string;
}
function populate_nexttwentyfour(data) {
    "use strict";
    var i, hourta, table_string="";
    for (i = 1; i < 25; i += 1) {
        hourta = data.hourly.data[i];
        table_string +=
            '<tr>\
                 <td>'+ convertTime(hourta.time, data.timezone) + '</td>\
                 <td><img src="' + image_path + map.image[hourta.icon] + '.png"\
                      alt="' + hourta.summary + '" title="' + hourta.summary + '" width="50px"></td>\
                 <td>' + die(Math.round(hourta.cloudCover), '%') + '</td>\
                 <td>' + die(hourta.temperature, '') + '</td>\
                 <td class="accordion-toggle" data-toggle="collapse"\
                        data-target="#details' + i + '">\
                     <i class="indicator glyphicon glyphicon-plus"></i>\
                 </td>\
             </tr>\
             <tr class="accordion-body collapse" id="details' + i + '">\
                 <td colspan="5">\
                     <table class="table table-condensed">\
                         <thead>\
                             <th class="text-center">Wind</th>\
                             <th class="text-center">Humidity</th>\
                             <th class="text-center">Visibility</th>\
                             <th class="text-center">Pressure</th>\
                         </thead>\
                         <tr>\
                             <td>' + die(hourta.windSpeed,
                                 map.unit[data.unit].windspeed) + '</td>\
                             <td>' + die(Math.round(hourta.humidity), '%') + '</td>\
                             <td>' + die(hourta.visibility,
                                 map.unit[data.unit].visibility) + '</td>\
                             <td>' + die(hourta.pressure,
                                 map.unit[data.unit].pressure) + '</td>\
                         </tr>\
                     </table>\
                 </td>\
             </tr>';
    }
    document.getElementById('nexttwentyfour-container').innerHTML = table_string;
    document.getElementById('table-temp-unit').innerText =
        document.getElementById('table-temp-unit').textContent =
        map.unit[data.unit].temperature;
}
function populate_fb(data) {
    "use strict";
    document.getElementById('fb_share').href = 'javascript:myFacebookLogin("' +
        data.$city + '","' + data.state + '","' + map.image[data.currently.icon] + '","' +
        data.currently.summary + '","' + data.currently.temperature + '","' +
        map.unit[data.unit].temperature + '")';
}
function populate_map(data) {
    document.getElementById("map").innerHTML = "";
    var layer_name = ["clouds", "precipitation"];
    var lat = data.latitude;
    var lon = data.longitude;
    var zoom = 14;
    var opacity = 0.2;

    imap = new OpenLayers.Map("map",
    {
        units:'m',
        projection: "EPSG:900913",
        displayProjection: new OpenLayers.Projection("EPSG:4326"),
    });

    var mapnik = new OpenLayers.Layer.OSM();

    var clouds = new OpenLayers.Layer.XYZ(
        "layer "+layer_name[0],
        "http://${s}.tile.openweathermap.org/map/" + layer_name[0] + "/${z}/${x}/${y}.png",
        {
            isBaseLayer: false,
            opacity: opacity,
            sphericalMercator: true
        }
    );

    var precip = new OpenLayers.Layer.XYZ(
        "layer "+layer_name[1],
        "http://${s}.tile.openweathermap.org/map/" + layer_name[1] + "/${z}/${x}/${y}.png",
        {
            isBaseLayer: false,
            opacity: opacity,
            sphericalMercator: true
        }
    );

    var centre = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"),
                                new OpenLayers.Projection("EPSG:900913"));
    imap.addLayers([mapnik, clouds, precip]);
    imap.setCenter(centre, zoom);
    imap.events.register("mousemove", imap, function (e) {
        var position = imap.getLonLatFromViewPortPx(e.xy).transform(new OpenLayers.Projection("EPSG:900913"),
                                new OpenLayers.Projection("EPSG:4326"));

        $("#mouseposition").html("Lat: " + Math.round(position.lat*100)/100 + " Lon: " + Math.round(position.lon*100)/100 +
            ' zoom: '+ imap.getZoom());
    });
}

function magic(data) {
    "use strict";
    document.getElementById('result').style.display = 'block';
    document.getElementById('map').style.height =
        document.getElementById('rightnow_container').scrollHeight - 20 + "px";
    populate_map(data);
    populate_fb(data);
    populate_rightnow(data);
    populate_nexttwentyfour(data);
    populate_nextseven(data);
}
