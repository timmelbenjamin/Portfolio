import { initMap, map } from '/scripts/utils/InitMap.js';
import { setElevationGraph, updateChartData } from '/scripts/utils/ElevationGraph.js';
import { createPath } from '/scripts/utils/CreatePath.js'

const mapZoomInBtn = document.getElementById('mapZoomIn')
const mapZoomOutBtn = document.getElementById('mapZoomOut')

const currentTemp = document.getElementById('currentTemp');
const minTemp = document.getElementById('minTemp');
const maxTemp = document.getElementById('maxTemp');
const weatherIcon = document.getElementById('weatherIcon');

const ctx = document.getElementById('elevation');

const parts = window.location.href.split('/');
const postId = parts[parts.length - 1];

const getGpxData_url = "http://127.0.0.1:8000/api/getGpxData"



let elevationDistanceData = []
let zoomValue = 11

mapZoomInBtn.addEventListener('click', function () {
    zoomValue += .5;
    map.setZoom(zoomValue);
});

mapZoomOutBtn.addEventListener('click', function () {
    if (zoomValue - 0.5 > 2.5) {
        zoomValue -= .5;
        map.setZoom(zoomValue);
    }
});


async function initializeData(postId, ctx) {

    const response = await fetch(getGpxData_url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ post: Number(postId) })
    });

    const gpx_json = await response.json();

    let gpxStartLat = gpx_json.gpx_data[0].lat;
    let gpxStartLon = gpx_json.gpx_data[0].lon;

    elevationDistanceData = gpx_json.gpx_data.map(point => ({
        ele: point.ele,
        distance: point.distance
    }));

    window.initMap = () => {
        initMap(gpxStartLat, gpxStartLon, zoomValue);
        createPath(map, gpx_json.gpx_data);
    };

    if (typeof google !== "undefined" && google.maps) {
        window.initMap();
    }

    setElevationGraph(ctx, elevationDistanceData);

    const weather_url = `https://api.openweathermap.org/data/2.5/weather?lat=${gpxStartLat}&lon=${gpxStartLon}&appid=8e56e77e3870568a65056c953a02453b`;
    
    const weather = await fetch(weather_url, {
        method: 'GET'
    });

    const weather_json = await weather.json();
    const iconId = weather_json.weather[0].icon;

    weatherIcon.src = `https://openweathermap.org/img/wn/${iconId}@2x.png`;
    currentTemp.innerHTML = (weather_json.main.temp - 273.15).toFixed(0) + "°C";

    minTemp.innerHTML = 'Min : ' + (weather_json.main.temp_min - 273.15).toFixed(0);
    maxTemp.innerHTML = 'Max : ' + (weather_json.main.temp_max - 273.15).toFixed(0);
}

initializeData(postId, ctx);

