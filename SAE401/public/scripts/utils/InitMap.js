export let map;  

export function initMap(gpxStartLat, gpxStartLon, zoomValue) {
    console.log("initMap exécuté !");

    map = new google.maps.Map(document.getElementById('carte'), {
        center: { lat: gpxStartLat, lng: gpxStartLon },
        zoom: zoomValue,
        mapTypeId: google.maps.MapTypeId.TERRAIN,
        disableDefaultUI: true,
        styles: [
            {
                "stylers": [
                    { "hue": "#baf4c4" },
                    { "saturation": 10 }
                ]
            },
            {
                "featureType": "water",
                "stylers": [
                    { "color": "#effefd" }
                ]
            },
            {
                "featureType": "all",
                "elementType": "labels",
                "stylers": [
                    { "visibility": "off" }
                ]
            },
            {
                "featureType": "administrative",
                "elementType": "labels",
                "stylers": [
                    { "visibility": "on" }
                ]
            },
            {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [
                    { "visibility": "off" }
                ]
            }
        ]
    });
}

