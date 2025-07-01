import { setElevationGraph, updateChartData } from '/scripts/utils/ElevationGraph.js';
import { initMap, map }  from '/scripts/utils/InitMap.js';
import { createPath } from '/scripts/utils/CreatePath.js'

const gpxInput = document.getElementById('posts_gpxFile')

const mapZoomInBtn = document.getElementById('mapZoomIn')
const mapZoomOutBtn = document.getElementById('mapZoomOut')

const emptyDropArea = document.getElementById('emptyDropArea')
const filledDropArea = document.getElementById('filledDropArea')

const ctx = document.getElementById('elevation')
const elevationContainer = document.getElementById('elevationContainer')

let gpxStartLat = 45.833641
let gpxStartLon = 6.864594
let zoomValue = 11
let elevationDistanceData = [];

const ElevationChart = setElevationGraph(ctx,elevationDistanceData)

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

gpxInput.addEventListener('change', async function (event) {

    const file = event.target.files[0];

    const url = "http://127.0.0.1:8000/api/upload_gpx"

    if (file) {

        const formData = new FormData();
        formData.append('gpx_file', file);

        emptyDropArea.style.display = 'none';
        filledDropArea.style.display = 'flex';
        filledDropArea.children[1].innerHTML = formData.get('gpx_file').name

        try {

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }

            const json = await response.json();
            let gpxData = json.gpx_data

            elevationDistanceData = gpxData.map(point => ({
                ele: point.ele,
                distance: point.distance
            }));

            elevationContainer.style.display = "flex"

            updateChartData(ElevationChart,elevationDistanceData);

            createPath(map, gpxData);


        } catch (error) {
            console.error(error.message);
        }

    }
});

window.initMap = () => {     
    initMap(gpxStartLat, gpxStartLon, zoomValue);  
}
