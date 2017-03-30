/**
 * Created by YongJin on 12/1/2016.
 */
var map;
var bounds;
var highPolygons ={}
var lowPolygons = {}
$(document).ready(function(){
    $(".btn").click(function(self){
        $.post('configs/Function.php',
        function(data, status){
            if(status == "success")
            {
                for (x in highPolygons)
                    highPolygons[x].setMap(null);
                for (x in lowPolygons)
                    lowPolygons[x].setMap(null);

                highPolygons = {}
                lowPolygons = {}

                result = JSON.parse(data)
                highArr = result["high"]
                lowArr = result["low"]
                var hfeatureStyle = {
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#FF0000',
                    fillOpacity: 0.35
                };
                var lfeatureStyle = {
                    strokeColor: '#00FF00',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#00FF00',
                    fillOpacity: 0.35
                };
                //bounds = new google.maps.LatLngBounds();
                for (x in highArr)
                {
                    coords = highArr[x];
                    wkt = new Wkt.Wkt();
                    hPoly = wkt.read(coords).toObject();
                    hPoly.setOptions(hfeatureStyle);
                    hPoly.setMap(map);
                    highPolygons[x] = hPoly;
                    extendBound(hPoly);
                    //processPoints(hPoly.getGeometry(), bounds.extend, bounds);

                    // if(hPoly.getGeometry().getType()==='Polygon') {
                        // hPoly.getGeometry().getArray().forEach(function (path) {

                            //iterate over the points in the path
                            // path.getArray().forEach(function (latLng) {

                                //extend the bounds
                                // bounds.extend(latLng);
                            // });
                        // });
                    // }

                }
                for (x in lowArr)
                {
                    coords = lowArr[x];
                    wkt = new Wkt.Wkt();
                    lPoly = wkt.read(coords).toObject();
                    lPoly.setOptions(lfeatureStyle);
                    lPoly.setMap(map);
                    lowPolygons[x] = lPoly;
					extendBound(lPoly);
                    //processPoints(lPoly.getGeometry(), bounds.extend, bounds);
                    // if(lPoly.getGeometry().getType()==='Polygon') {
                        // lPoly.getGeometry().getArray().forEach(function (path) {

                            //iterate over the points in the path
                            // path.getArray().forEach(function (latLng) {

                                //extend the bounds
                                // bounds.extend(latLng);
                            // });
                        // });
                    // }

                }
                ///////////////map bound function///////////////////////////
                map.fitBounds(bounds);
            }
        });
    })
})


// your POLYGON
function initialize() {
    map = new google.maps.Map(
        document.getElementById("map_canvas"), {
            center: new google.maps.LatLng(37.4419, -122.1419),
            zoom: 5,
            mapTypeId: 'terrain'
        });
    bounds = new google.maps.LatLngBounds();
    map.data.addListener('addfeature', function(e) {
        processPoints(e.feature.getGeometry(), bounds.extend, bounds);
        map.fitBounds(bounds);
    });

    // zoom to the clicked feature
    map.data.addListener('click', function(e) {
        var bounds = new google.maps.LatLngBounds();
        processPoints(e.feature.getGeometry(), bounds.extend, bounds);
        map.fitBounds(bounds);
    });
}
function processPoints(geometry, callback, thisArg) {
    if (geometry instanceof google.maps.LatLng) {
        callback.call(thisArg, geometry);
    } else if (geometry instanceof google.maps.Data.Point) {
        callback.call(thisArg, geometry.get());
    } else {
        geometry.getArray().forEach(function(g) {
            processPoints(g, callback, thisArg);
        });
    }
}
function extendBound(pol)
{
    pol.getPaths().getArray().forEach(function(path) {

        //iterate over the points in the path
        path.getArray().forEach(function (latLng) {

            //extend the bounds
            bounds.extend(latLng);
        });
    });
}

google.maps.event.addDomListener(window, "load", initialize);
