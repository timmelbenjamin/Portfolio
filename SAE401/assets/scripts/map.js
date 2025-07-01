// Fonction d'initialisation de la carte
function initMap(lat = 48.5734, lng = 7.7521, tab_points = itineraires) {
    // Initialisation de la carte centrée sur Strasbourg
    var map = new google.maps.Map(document.getElementById('carte'), {
        center: { lat, lng }, // Coordonnées par défaut
        zoom: 12,
        mapTypeControl: false,
        fullscreenControl: false,
        streetViewControl: false,
        styles: [
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{ "visibility": "on" }, { "color": "#aee2e0" }]
            },
            {
                "featureType": "landscape",
                "elementType": "geometry.fill",
                "stylers": [{ "color": "#abce83" }]
            },
            {
                "featureType": "poi",
                "elementType": "geometry.fill",
                "stylers": [{ "color": "#769E72" }]
            }
        ]
    });

    // Création de l'instance de Geocoder
    const geocoder = new google.maps.Geocoder();

    // Vérification si tab_points est vide
    if (tab_points.length === 0) {
        console.log("Aucun itinéraire à afficher.");
        return;
    }

    // Ajout des points sur la carte à partir des données JSON
    tab_points.forEach((itineraire) => {
        geocoder.geocode({ 'address': itineraire.city }, function (results, status) {
            if (status === 'OK') {
                // Récupération des coordonnées latitude et longitude
                const latLng = results[0].geometry.location;

                // Création du marker pour chaque itinéraire
                const marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    title: itineraire.title,
                });

                // Ajout d'un infowindow pour afficher les détails de l'itinéraire
                const infowindow = new google.maps.InfoWindow({
                    content: `
                        <h3>${itineraire.title}</h3>
                        <p>${itineraire.description}</p>
                        <p><strong>Région:</strong> ${itineraire.region}</p>
                        <p><strong>Ville:</strong> ${itineraire.city}</p>
                    `,
                });

                // Lier l'infowindow au marker
                marker.addListener("click", function () {
                    infowindow.open(map, marker);
                });
            }
        });
    });

    console.log(tab_points); // Vérifie dans la console si les données sont bien récupérées
}
