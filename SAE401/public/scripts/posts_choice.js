import { initMap } from '/scripts/utils/InitMap.js';

async function getCenterLocation(location) {
    const url = `https://maps.googleapis.com/maps/api/geocode/json?address=${location}&key=AIzaSyDsRQNVaLvck3Jhk-LqJqfkUY6cpKOA-3c`;

    try {
        const response = await fetch(url, {
            method: 'GET',
        });

        if (!response.ok) {
            throw new Error('error');
        }

        const centerPointData = await response.json();
        return centerPointData
    } catch (error) {
        console.error(error);
        return null;
    }
}

async function initializeMap() {
    
    const centerPoint = await getCenterLocation('Chamonix');

    console.log(centerPoint)

    let gpxStartLat = centerPoint.results[0].geometry.location.lat;
    let gpxStartLon = centerPoint.results[0].geometry.location.lng;

    window.initMap = () => {
        initMap(gpxStartLat, gpxStartLon, 11);
    };

    if (typeof google !== "undefined" && google.maps) {
        window.initMap();
    }
}

initializeMap();