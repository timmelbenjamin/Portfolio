<?php 

require_once '../vendor/autoload.php'; // Chargez l'autoloader de Composer

// Configuration de Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader);

include('../include/connexion.php');
include('../cours.php');
include('../materiel.php');
include('../tuto.php');

// Récupération des paramètres de l'URL
$page = isset($_GET['page']) ? $_GET['page'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : 'read';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_utilisateur = isset($_GET['id_utilisateur']) ? $_GET['id_utilisateur'] : '';
$role = isset($_GET['role']) ? $_GET['role'] : '';

// Variables à passer à Twig
$templateData = [
    'page' => $page,
    'action' => $action,
    'id' => $id,
    'id_utilisateur' => $id_utilisateur,
    'role' => $role,
    'id_article' => null,
    'bloc' => null,
    'cours' => null,
    'materiel' => null,
    'tuto' => null,
    'tableau_materiel' => Materiel::readAll(),
    'tableau_cours' => Cours::readAll(),
    'tableau_tuto' => Tuto::readAll(),
    'message' => null
];


// Affichage du template
echo $twig->render('mentions-legales.twig', $templateData);
?>