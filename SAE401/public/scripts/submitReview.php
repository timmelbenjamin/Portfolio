<?php
// Vérifie si la requête est en POST et que les données sont présentes
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupère les données JSON envoyées dans la requête
    $data = json_decode(file_get_contents('php://input'), true);

    // Vérifie que les données sont valides
    if (isset($data['rating']) && isset($data['itineraryId'])) {
        // Ici, tu peux enregistrer les données dans la base de données
        // Par exemple : sauvegarder l'avis en base de données

        // Réponds à la requête avec un succès
        echo json_encode(['message' => 'Avis soumis avec succès']);
        http_response_code(200);
    } else {
        // Si les données sont invalides
        echo json_encode(['error' => 'Données manquantes']);
        http_response_code(400);
    }
} else {
    echo json_encode(['error' => 'Méthode non autorisée']);
    http_response_code(405);
}
