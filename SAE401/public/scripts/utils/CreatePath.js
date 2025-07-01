let flightPath = null;
let startMarker = null;
let endMarker = null;

export function createPath(map, gpxData) {

    let gpxStartLat = gpxData[0]["lat"]
    let gpxStartLon = gpxData[0]["lon"]

    let gpxEndLat = gpxData[gpxData.length - 1]["lat"]
    let gpxEndLon = gpxData[gpxData.length - 1]["lon"]

    if (flightPath) {
        flightPath.setMap(null);
        flightPath = null;
    }
    if (startMarker) {
        startMarker.setMap(null);
        startMarker = null;
    }
    if (endMarker) {
        endMarker.setMap(null);
        endMarker = null;
    }

    map.setCenter({ lat: gpxStartLat, lng: gpxStartLon });
    map.setZoom(13);

    const flightPlanCoordinates = gpxData.map(point => ({
        lat: point.lat,
        lng: point.lon
    }));

    flightPath = new google.maps.Polyline({
        path: flightPlanCoordinates,
        geodesic: true,
        strokeColor: "#111111",
        strokeOpacity: 1.0,
        strokeWeight: 4,
    });


    flightPath.setMap(map);

    let startPoint = new google.maps.LatLng(gpxStartLat, gpxStartLon);
    let endPoint = new google.maps.LatLng(gpxEndLat, gpxEndLon);

    startMarker = new google.maps.Marker({
        map: map,
        position: startPoint,
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 8,
            fillColor: "#63F679",
            fillOpacity: 1,
            strokeWeight: 2,
            strokeColor: "#63F679"
        }
    });

    endMarker = new google.maps.Marker({
        map: map,
        position: endPoint,
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 8,
            fillColor: "#121212",
            fillOpacity: 1,
            strokeWeight: 2,
            strokeColor: "#121212"
        }
    });

    return { flightPath, startMarker, endMarker };

}