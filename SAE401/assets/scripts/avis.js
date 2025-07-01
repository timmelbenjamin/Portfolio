console.log("Le script avis.js est bien chargé !");

document.addEventListener("DOMContentLoaded", function () {
    console.log("Script chargé");

    const reviewButtons = document.querySelectorAll("[id^='review-btn-']");

    console.log("Nombre de boutons détectés:", reviewButtons.length);

    reviewButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            const itineraryId = this.getAttribute("data-itinerary-id");
            const formContainer = document.getElementById(`review-form-container-${itineraryId}`);

            console.log(`Bouton cliqué: ${this.id}, formulaire cible: review-form-container-${itineraryId}`);

            if (formContainer.classList.contains("hidden")) {
                createReviewForm(formContainer, itineraryId);
                formContainer.classList.remove("hidden");
            } else {
                formContainer.classList.add("hidden");
            }
        });
    });
});

function createReviewForm(container, itineraryId) {
    container.innerHTML = ""; // Vider avant d'ajouter le formulaire

    const formHTML = `
        <div class="bg-white p-6 rounded-lg shadow-xl mt-6 w-full max-w-lg mx-auto transition-all duration-300 hover:shadow-2xl">
    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Votre avis</h3>

    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
        <input type="text" id="review-title-${itineraryId}" 
            class="text-black w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all" required>
    </div>

    <div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
    <div class="flex items-center justify-center space-x-2" id="rating-stars-${itineraryId}">
        ${[1, 2, 3, 4, 5]
        .map((num) => `<span class="star cursor-pointer text-3xl text-gray-400 hover:text-yellow-500 transition-colors" data-value="${num}">☆</span>`)
        .join("")}
        <input type="hidden" id="review-rating-${itineraryId}" value="0">
    </div>
</div>


    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Date de visite</label>
        <input type="date" id="review-date-${itineraryId}" 
            class="text-black w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all" required>
    </div>

    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Votre message</label>
        <textarea id="review-message-${itineraryId}" rows="4" 
            class="text-black w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all" required></textarea>
    </div>

    <div class="flex justify-end space-x-4 mt-4">
        <button id="cancel-review-${itineraryId}" 
            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
            Annuler
        </button>
        <button id="submit-review-${itineraryId}" 
            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
            Envoyer
        </button>
    </div>
</div>

    `;

    container.innerHTML = formHTML;
    setupStarRating(itineraryId);

    document.getElementById(`cancel-review-${itineraryId}`).addEventListener("click", function () {
        container.classList.add("hidden");
    });

    document.getElementById(`submit-review-${itineraryId}`).addEventListener("click", function () {
        submitReviewForm(itineraryId, container);
    });

    console.log(`Formulaire affiché pour l'ID: ${itineraryId}`);
}

function setupStarRating(itineraryId) {
    const stars = document.querySelectorAll(`#rating-stars-${itineraryId} .star`);
    const ratingInput = document.getElementById(`review-rating-${itineraryId}`);

    stars.forEach((star) => {
        star.addEventListener("mouseover", function () {
            highlightStars(stars, this.getAttribute("data-value"));
        });

        star.addEventListener("mouseout", function () {
            highlightStars(stars, ratingInput.value);
        });

        star.addEventListener("click", function () {
            ratingInput.value = this.getAttribute("data-value");
            highlightStars(stars, ratingInput.value);
        });
    });
}

function highlightStars(stars, value) {
    stars.forEach((star) => {
        star.textContent = star.getAttribute("data-value") <= value ? "★" : "☆";
        star.classList.toggle("text-black", star.getAttribute("data-value") <= value);
    });
}

async function submitReviewForm(itineraryId, container) {
    const title = document.getElementById(`review-title-${itineraryId}`).value;
    const rating = document.getElementById(`review-rating-${itineraryId}`).value;
    const date = document.getElementById(`review-date-${itineraryId}`).value;
    const message = document.getElementById(`review-message-${itineraryId}`).value;

    if (!title || rating === "0" || !date || !message) {
        alert("Veuillez remplir tous les champs et donner une note.");
        return;
    }

    const reviewData = { title, rating: parseInt(rating), visitDate: date, message, itineraryId };

    try {
        const response = await fetch("/submit-review", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(reviewData),
        });

        if (!response.ok) {
            const errorText = await response.text();  // Récupérer la réponse sous forme de texte
            console.error("Erreur lors de l'envoi :", errorText);
            throw new Error("Erreur lors de l'envoi");
        }

        const result = await response.json();
        alert(result.message);

    } catch (error) {
        console.error("Erreur:", error);
    }
}
