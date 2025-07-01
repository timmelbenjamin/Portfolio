<?php 

require_once 'vendor/autoload.php'; // Chargez l'autoloader de Composer

// Configuration de Twig
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include('include/connexion.php');
include('cours.php');
include('materiel.php');
include('tuto.php');

// Récupération des paramètres de l'URL
$page = isset($_GET['page']) ? $_GET['page'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : 'read';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_utilisateur = isset($_GET['id_utilisateur']) ? $_GET['id_utilisateur'] : 'read';
$role = isset($_GET['role']) ? $_GET['role'] : '';

// Variables à passer à Twig
$templateData = [
    'page' => $page,
    'action' => $action,
    'id' => $id,
    'id_utilisateur' => $id_utilisateur,
    'role' => $role,
    'id_article' => null,
    'cours' => null,
    'materiel' => null,
    'tuto' => null,
    'tableau_materiel' => Materiel::readAll(),
    'tableau_cours' => Cours::readAll(),
    'tableau_tuto' => Tuto::readAll(),
    'messages' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $messageContent = htmlspecialchars(trim($_POST['message']));

    if (empty($nom) || empty($email) || empty($messageContent)) {
        $templateData['message'] = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $templateData['message'] = 'Adresse e-mail invalide.';
    } else {
        $templateData['message'] = 'Merci pour votre message, nous vous répondrons bientôt.';
    }
}


echo $twig->render('contact_visiteurs.twig', $templateData);
?>