import { initMap, map } from '/scripts/utils/InitMap.js';
import { setElevationGraph, updateChartData } from '/scripts/utils/ElevationGraph.js';
import { createPath } from '/scripts/utils/CreatePath.js'

const mapZoomInBtn = document.getElementById('mapZoomIn')
const mapZoomOutBtn = document.getElementById('mapZoomOut')

const itineraires = document.querySelectorAll(".itinerary");
const activityButtons = document.querySelectorAll(".activityButton")
const noneinput = document.getElementById("activities_placeholder")
const resetButton = document.getElementById('resetButton');


const url = "http://127.0.0.1:8000/api/getGpxData"

let svg
let zoomValue = 11
let elevationDistanceData = [];
let gpxData


const label_activities_2 = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="18" viewBox="0 0 14 18" fill="none">
    <path d="M5.27026 14.0646L4.83647 16.8802C4.81054 17.0477 4.72422 17.2005 4.59318 17.3109C4.46213 17.4214 4.29503 17.4822 4.12216 17.4823C4.0856 17.4823 4.04909 17.4794 4.01299 17.4738C3.8236 17.4455 3.65342 17.3447 3.53982 17.1936C3.42622 17.0424 3.3785 16.8533 3.40712 16.6677L3.84092 13.8521C3.85351 13.7587 3.88498 13.6688 3.93347 13.5875C3.98197 13.5062 4.04652 13.4352 4.12336 13.3786C4.2002 13.3221 4.28778 13.2811 4.38097 13.258C4.47417 13.235 4.57111 13.2305 4.66613 13.2446C4.76116 13.2587 4.85235 13.2913 4.93438 13.3403C5.01642 13.3894 5.08764 13.454 5.14389 13.5303C5.20014 13.6067 5.24029 13.6933 5.26199 13.785C5.28368 13.8767 5.2865 13.9718 5.27026 14.0646ZM13.5 6.16667V16.7917C13.5 16.9795 13.4238 17.1597 13.2882 17.2925C13.1527 17.4254 12.9688 17.5 12.777 17.5C12.5853 17.5 12.4014 17.4254 12.2658 17.2925C12.1302 17.1597 12.054 16.9795 12.054 16.7917V9H9.88507C9.75072 9.00006 9.61901 8.96344 9.50471 8.89425C9.39041 8.82506 9.29806 8.72604 9.238 8.60829L8.77746 7.70517L8.44561 9.49017C8.41532 9.65326 8.32762 9.80077 8.19776 9.90703C8.0679 10.0133 7.90411 10.0716 7.73491 10.0717C7.69129 10.0714 7.64777 10.0676 7.60478 10.0604C7.4166 10.026 7.25003 9.91984 7.14161 9.76527C7.03318 9.61071 6.99174 9.42033 7.02639 9.23588L7.5961 6.17304C7.57947 6.17304 7.56429 6.16667 7.54838 6.16667H6.15302L5.44016 9.96333C5.41065 10.1032 5.42497 10.2484 5.48122 10.3802C5.53747 10.5119 5.63303 10.6238 5.75538 10.7014L8.82951 12.6557C8.93056 12.7201 9.01358 12.8081 9.07103 12.9119C9.12848 13.0157 9.15854 13.1319 9.15847 13.25V16.7917C9.15847 16.9795 9.0823 17.1597 8.94672 17.2925C8.81113 17.4254 8.62723 17.5 8.43549 17.5C8.24374 17.5 8.05985 17.4254 7.92426 17.2925C7.78867 17.1597 7.7125 16.9795 7.7125 16.7917V13.6346L4.96516 11.89C4.75886 11.7573 4.57719 11.591 4.42798 11.3984C4.01428 11.6878 3.51704 11.8402 3.00876 11.8333C1.80065 11.8333 0.5 10.9465 0.5 9C0.5 6.61717 2.69571 4.75 5.49872 4.75H7.54549C7.94913 4.74804 8.34522 4.85706 8.68877 5.06467C9.03231 5.27228 9.30952 5.57015 9.48888 5.92442L10.3319 7.58333H12.054V6.16667C12.054 5.97881 12.1302 5.79864 12.2658 5.6658C12.4014 5.53296 12.5853 5.45833 12.777 5.45833C12.9688 5.45833 13.1527 5.53296 13.2882 5.6658C13.4238 5.79864 13.5 5.97881 13.5 6.16667ZM4.04986 9.56171L4.66512 6.24954C3.22204 6.5265 1.94597 7.5125 1.94597 9C1.94597 10.3458 2.83163 10.4167 3.00876 10.4167C3.25607 10.4128 3.4945 10.3257 3.68406 10.1701C3.87362 10.0144 4.00278 9.79961 4.04986 9.56171ZM7.71612 4.04167C8.0736 4.04167 8.42305 3.93781 8.72029 3.74323C9.01753 3.54865 9.24919 3.27208 9.38599 2.9485C9.5228 2.62493 9.55859 2.26887 9.48885 1.92536C9.41911 1.58185 9.24697 1.26632 8.99419 1.01867C8.74141 0.77101 8.41935 0.602355 8.06873 0.534027C7.71812 0.465699 7.3547 0.500767 7.02443 0.634797C6.69416 0.768828 6.41187 0.9958 6.21327 1.28701C6.01466 1.57822 5.90865 1.9206 5.90865 2.27083C5.90865 2.74049 6.09908 3.19091 6.43805 3.523C6.77701 3.8551 7.23675 4.04167 7.71612 4.04167Z" fill="black" />
</svg>`

const label_activities_3 = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 16 18" fill="none">
    <path d="M15.5 8.29167V9.70833H12.2393L10.6129 7.02942L9.21857 10.3841L7.99286 9.61554L9.42857 6.16667H8.08571L6.60857 9.731C6.54474 9.88503 6.53767 10.0564 6.58862 10.2151C6.63956 10.3738 6.74526 10.5096 6.88714 10.5987L10.4929 12.8597V17.5H9.06429V13.6403L6.12429 11.7986C5.69764 11.5317 5.37963 11.1241 5.22624 10.6475C5.07284 10.171 5.0939 9.65605 5.28571 9.19337L6.53857 6.16667H4.51286L3.28214 8.60829L2.00357 7.97504L3.63 4.75H9.68571C10.055 4.75107 10.4178 4.8463 10.7393 5.02654C11.0608 5.20678 11.3301 5.46597 11.5214 5.77921L13.0464 8.29167H15.5ZM10.5 4.04167C10.8532 4.04167 11.1984 3.93781 11.4921 3.74323C11.7857 3.54865 12.0146 3.27208 12.1498 2.9485C12.2849 2.62493 12.3203 2.26887 12.2514 1.92536C12.1825 1.58185 12.0124 1.26632 11.7627 1.01867C11.513 0.77101 11.1948 0.602355 10.8484 0.534027C10.502 0.465699 10.1429 0.500767 9.81664 0.634797C9.49034 0.768828 9.21145 0.9958 9.01523 1.28701C8.81902 1.57822 8.71429 1.9206 8.71429 2.27083C8.71429 2.74049 8.90242 3.19091 9.23731 3.523C9.5722 3.8551 10.0264 4.04167 10.5 4.04167ZM4.66071 12.4L4.30786 13.25H0.5V14.6667H5.26357L5.835 13.2911L5.36 12.9936C5.102 12.8265 4.86694 12.627 4.66071 12.4Z" fill="black" />
</svg>`

const label_activities_4 = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
  <path d="M11.8371 2.27085C11.8371 1.92061 11.9411 1.57824 12.136 1.28702C12.3309 0.995805 12.6078 0.76883 12.9319 0.634799C13.2559 0.500767 13.6125 0.465699 13.9565 0.534027C14.3005 0.602356 14.6165 0.771013 14.8645 1.01867C15.1126 1.26633 15.2815 1.58187 15.3499 1.92538C15.4183 2.26889 15.3832 2.62495 15.249 2.94853C15.1147 3.27211 14.8874 3.54868 14.5958 3.74326C14.3042 3.93785 13.9613 4.0417 13.6105 4.0417C13.1402 4.0417 12.6891 3.85513 12.3565 3.52303C12.024 3.19094 11.8371 2.74051 11.8371 2.27085ZM16.2203 15.2788C16.1396 15.4468 16.0266 15.5973 15.8876 15.7216C15.7487 15.8459 15.5865 15.9416 15.4105 16.0033C15.2344 16.0649 15.0479 16.0913 14.8616 16.0809C14.6753 16.0705 14.4929 16.0234 14.3249 15.9425L8.81947 13.3344L10.8185 10.0881L8.44492 8.34133C8.12003 8.00062 8.44492 7.60253 8.52792 7.50974L8.77619 7.2682L10.4155 8.04737V6.47911L9.86366 6.21419L10.4092 5.68293C10.4771 5.60842 10.5606 5.5497 10.6538 5.5109C10.7469 5.47211 10.8474 5.45418 10.9483 5.45839C11.1222 5.46864 11.286 5.54313 11.4079 5.66735L11.8102 5.97264V8.72596L14.9973 10.188L15.589 8.90021L13.2289 7.81787V5.26784L12.3067 4.57084C11.9454 4.2456 11.4806 4.05821 10.9944 4.0417C10.6971 4.03206 10.4011 4.08475 10.1254 4.19639C9.84978 4.30802 9.60063 4.47612 9.39405 4.68984L8.49741 5.56464L4.71223 3.76333L5.24993 2.62998L3.9681 2.02435L3.43111 3.15415L2.43374 2.67957L1.82581 3.95954L2.82318 4.43413L2.28548 5.56747L3.56731 6.17452L4.1043 5.04117L7.43194 6.62715C7.25406 6.81314 7.11465 7.03232 7.02169 7.27217C6.92872 7.51201 6.88402 7.7678 6.89015 8.02491C6.89628 8.28201 6.95311 8.53539 7.0574 8.77055C7.16168 9.0057 7.31138 9.21801 7.49791 9.39534L8.92729 10.4579L7.52983 12.7245L1.10793 9.67868L0.5 10.9615L13.7141 17.2211C14.0501 17.3821 14.4145 17.4754 14.7866 17.4958C15.1587 17.5161 15.5311 17.4631 15.8827 17.3397C16.2342 17.2163 16.558 17.0249 16.8355 16.7766C17.113 16.5282 17.3388 16.2277 17.5 15.8922L16.2203 15.2788Z" fill="black"/>
</svg>`

const label_activities_5 = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
  <path d="M3.75 10.1087C3.00832 10.1087 2.2833 10.3254 1.66661 10.7315C1.04993 11.1376 0.569282 11.7148 0.285453 12.3901C0.00162482 13.0654 -0.0726377 13.8084 0.0720569 14.5253C0.216751 15.2422 0.573904 15.9007 1.09835 16.4176C1.6228 16.9344 2.29098 17.2864 3.01841 17.429C3.74584 17.5716 4.49984 17.4984 5.18506 17.2187C5.87029 16.939 6.45596 16.4653 6.86801 15.8575C7.28007 15.2498 7.5 14.5353 7.5 13.8043C7.49881 12.8246 7.10334 11.8852 6.40034 11.1924C5.69733 10.4996 4.7442 10.1099 3.75 10.1087ZM3.75 16.0217C3.30499 16.0217 2.86998 15.8917 2.49997 15.648C2.12996 15.4044 1.84157 15.0581 1.67127 14.6529C1.50098 14.2477 1.45642 13.8019 1.54323 13.3718C1.63005 12.9416 1.84434 12.5465 2.15901 12.2364C2.47368 11.9263 2.87459 11.7151 3.31105 11.6296C3.74751 11.544 4.19991 11.5879 4.61104 11.7557C5.02217 11.9236 5.37357 12.2078 5.62081 12.5724C5.86804 12.9371 6 13.3658 6 13.8043C6 14.3924 5.76295 14.9564 5.34099 15.3723C4.91903 15.7881 4.34674 16.0217 3.75 16.0217ZM14.25 10.1087C13.5083 10.1087 12.7833 10.3254 12.1666 10.7315C11.5499 11.1376 11.0693 11.7148 10.7855 12.3901C10.5016 13.0654 10.4274 13.8084 10.5721 14.5253C10.7168 15.2422 11.0739 15.9007 11.5984 16.4176C12.1228 16.9344 12.791 17.2864 13.5184 17.429C14.2458 17.5716 14.9998 17.4984 15.6851 17.2187C16.3703 16.939 16.956 16.4653 17.368 15.8575C17.7801 15.2498 18 14.5353 18 13.8043C17.9988 12.8246 17.6033 11.8852 16.9003 11.1924C16.1973 10.4996 15.2442 10.1099 14.25 10.1087ZM14.25 16.0217C13.805 16.0217 13.37 15.8917 13 15.648C12.63 15.4044 12.3416 15.0581 12.1713 14.6529C12.001 14.2477 11.9564 13.8019 12.0432 13.3718C12.13 12.9416 12.3443 12.5465 12.659 12.2364C12.9737 11.9263 13.3746 11.7151 13.811 11.6296C14.2475 11.544 14.6999 11.5879 15.111 11.7557C15.5222 11.9236 15.8736 12.2078 16.1208 12.5724C16.368 12.9371 16.5 13.3658 16.5 13.8043C16.5 14.3924 16.2629 14.9564 15.841 15.3723C15.419 15.7881 14.8467 16.0217 14.25 16.0217ZM7.773 8.83517C7.69474 8.76941 7.63122 8.68831 7.58649 8.59704C7.54177 8.50577 7.51681 8.40632 7.51321 8.30502C7.50961 8.20372 7.52745 8.1028 7.56559 8.00866C7.60374 7.91453 7.66134 7.82925 7.73475 7.75826C7.8615 7.64 9.7245 5.95478 9.7245 5.95478L12.2197 8.41387C12.3612 8.54851 12.5507 8.62301 12.7473 8.62132C12.9439 8.61964 13.1321 8.54191 13.2711 8.40487C13.4102 8.26782 13.489 8.08244 13.4908 7.88864C13.4925 7.69485 13.4169 7.50814 13.2803 7.36874L10.2803 4.41222C10.2516 4.3833 10.2205 4.35684 10.1873 4.33313C9.38396 3.7727 8.4264 3.46691 7.44225 3.45652C6.39075 3.45652 3.75 4.33609 3.75 6.41304C3.75 7.00113 3.98705 7.56513 4.40901 7.98098C4.83097 8.39682 5.40326 8.63043 6 8.63043C6.018 8.63043 6.0345 8.62674 6.05175 8.626C6.13264 9.14229 6.3965 9.61364 6.7965 9.95643L8.25 11.1871V13.8043C8.25 14.0004 8.32902 14.1884 8.46967 14.327C8.61032 14.4656 8.80109 14.5435 9 14.5435C9.19891 14.5435 9.38968 14.4656 9.53033 14.327C9.67098 14.1884 9.75 14.0004 9.75 13.8043V10.8478C9.74999 10.7412 9.7266 10.6359 9.68142 10.5391C9.63624 10.4423 9.57034 10.3562 9.48825 10.2868L7.773 8.83517ZM5.25 6.41304C5.25 5.51574 6.879 4.93478 7.44225 4.93478C7.78403 4.93657 8.12322 4.99324 8.4465 5.10257C8.4465 5.10257 6.7095 6.67987 6.68925 6.69909C6.4035 6.96665 6.16875 7.15217 6 7.15217C5.80109 7.15217 5.61032 7.0743 5.46967 6.93569C5.32902 6.79707 5.25 6.60907 5.25 6.41304ZM10.5 2.34783C10.5 1.98236 10.61 1.6251 10.816 1.32123C11.022 1.01736 11.3149 0.780516 11.6575 0.640658C12.0001 0.500801 12.3771 0.464208 12.7408 0.535506C13.1045 0.606805 13.4386 0.782793 13.7008 1.04122C13.963 1.29964 14.1416 1.62889 14.214 1.98733C14.2863 2.34578 14.2492 2.71731 14.1073 3.05496C13.9654 3.39261 13.725 3.6812 13.4167 3.88424C13.1084 4.08728 12.7458 4.19565 12.375 4.19565C11.8777 4.19565 11.4008 4.00097 11.0492 3.65444C10.6975 3.3079 10.5 2.8379 10.5 2.34783Z" fill="black"/>
</svg>`


let urlParams = new URLSearchParams(window.location.search);
let location = urlParams.get('location');

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

activityButtons.forEach((activityButton) => {

    if (activityButton.id === "label_activities_2") {
        svg = label_activities_2;
    } else if (activityButton.id === "label_activities_3") {
        svg = label_activities_3;
    } else if (activityButton.id === "label_activities_4") {
        svg = label_activities_4;
    } else if (activityButton.id === "label_activities_5") {
        svg = label_activities_5;
    }

    if (svg) {
        activityButton.insertAdjacentHTML("afterbegin", svg);
    }

    const input = activityButton.querySelector('input');
    if (input.checked) {
        activityButton.classList.add("selected");
    }
});


activityButtons.forEach((activityButton) => {
    activityButton.addEventListener('click', (e) => {
        e.stopPropagation()
        e.preventDefault()
        const input = activityButton.querySelector('input');

        if (input.checked) {
            noneinput.checked = true;
        } else {
            input.checked = true;
        }

        activityButtons.forEach((OtheractivityButton) => {
            if (OtheractivityButton.id !== activityButton.id) {
                OtheractivityButton.classList.remove("selected");
            }
        });

        activityButton.classList.toggle("selected");

    });
});

if (resetButton) {
    resetButton.addEventListener('click', () => {
        itineraires.forEach((itinerary) => {
            const isFullWidth = itinerary.classList.contains('fullWidthItem');
            const details = itinerary.querySelector('.fullWidthInfo');

            if (isFullWidth) {
                itinerary.classList.remove('fullWidthItem');
                details.style.display = "none";
            }

            itinerary.style.display = "flex";
        });

        map.setZoom(11);
        resetButton.style.display = "none";
    });
}

itineraires.forEach((itinerary) => {
    gpxData = null;

    itinerary.addEventListener('click', async () => {
        const isFullWidth = itinerary.classList.contains('fullWidthItem');
        const details = itinerary.querySelector('.fullWidthInfo');
        const currentTemp = itinerary.querySelector('#currentTemp');
        const minTemp = itinerary.querySelector('#minTemp');
        const maxTemp = itinerary.querySelector('#maxTemp');
        const weatherIcon = itinerary.querySelector('#weatherIcon');
        let elevationContainer = itinerary.querySelector('#elevationContainer');
        let ctx = itinerary.querySelector('#elevation');

        if (window.ElevationChart instanceof Chart) {
            window.ElevationChart.destroy();
        }

        window.ElevationChart = setElevationGraph(ctx, elevationDistanceData);

        itineraires.forEach((otherItinerary) => {
            if (otherItinerary.id !== itinerary.id) {
                if (isFullWidth) {
                } else {
                    otherItinerary.style.display = "none";
                }
            }
        });

        if (isFullWidth) {
        }
        else {
            itinerary.classList.add('fullWidthItem');
            details.style.display = "flex";
            resetButton.style.display = "block";

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ post: Number(itinerary.id) })
            });

            const json = await response.json();
            console.log(json.gpx_data);
            gpxData = json.gpx_data;
            createPath(map, gpxData);

            const weather_url = `https://api.openweathermap.org/data/2.5/weather?lat=${gpxData[0].lat}&lon=${gpxData[0].lon}&appid=8e56e77e3870568a65056c953a02453b`;

            const weather = await fetch(weather_url, {
                method: 'GET'
            });

            const weather_json = await weather.json();
            console.log(weather_json);

            currentTemp.innerHTML = (weather_json.main.temp - 273.15).toFixed(0) + "°C";
            minTemp.innerHTML = 'Min : ' + (weather_json.main.temp_min - 273.15).toFixed(0);
            maxTemp.innerHTML = 'Max : ' + (weather_json.main.temp_max - 273.15).toFixed(0);
            let iconId = weather_json.weather[0].icon;
            weatherIcon.src = `https://openweathermap.org/img/wn/${iconId}@2x.png`;

            elevationDistanceData = gpxData.map(point => ({
                ele: point.ele,
                distance: point.distance
            }));

            elevationContainer.style.display = "flex";
            updateChartData(ElevationChart, elevationDistanceData);
        }
    });
});


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

async function initializeMap(location) {
    const centerPoint = await getCenterLocation(location);

    let gpxStartLat = centerPoint.results[0].geometry.location.lat;
    let gpxStartLon = centerPoint.results[0].geometry.location.lng;

    window.initMap = () => {
        initMap(gpxStartLat, gpxStartLon, zoomValue);
    };

    if (typeof google !== "undefined" && google.maps) {
        window.initMap();
    }
}

initializeMap(location);