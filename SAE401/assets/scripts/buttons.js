let filtre_actif= " ";

document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner uniquement les boutons dans le conteneur ayant la classe 'activity-buttons'
    const buttons = document.querySelectorAll('.activity-buttons button');

    // Fonction pour gérer le clic sur un bouton
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            if (button.classList.contains('greenBtn-activity')) {
                // Si le bouton est déjà vert, on retire la classe
                button.classList.remove('greenBtn-activity');
                filtre_actif = " ";
            } else {
                // Supprimez la classe 'greenBtn' de tous les boutons
                buttons.forEach(btn => btn.classList.remove('greenBtn-activity'));

                // Ajoutez la classe 'greenBtn' au bouton cliqué
                button.classList.add('greenBtn-activity');
                if (button.id == 'btn-rando') {
                    filtre_actif = "Randonnée";
                } else if (button.id == 'btn-trail') {
                    filtre_actif = "Trail";
                } else if (button.id == 'btn-ski') {
                    filtre_actif = "Ski";
                } else if (button.id == 'btn-velo') {
                    filtre_actif = "Vélo";
                }

            }

                searchItinerary();


                // Ajoute la classe 'greenBtn-activity' au bouton cliqué
                button.classList.add('greenBtn-activity');
            }
        });
    });
});

