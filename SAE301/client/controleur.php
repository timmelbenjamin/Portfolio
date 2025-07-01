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
$filtre = isset($_GET['filtre']) ? ($_GET['filtre']) : '';
$id_utilisateur = isset($_GET['id_utilisateur']) ? $_GET['id_utilisateur'] : '';
$role = isset($_GET['role']) ? $_GET['role'] : '';

// Variables à passer à Twig
$templateData = [
    'page' => $page,
    'action' => $action,
    'id' => $id,
    'id_utilisateur' => $id_utilisateur,
    'role' => $role,
    'filtre' => $filtre,
    'id_article' => null,
    'cours' => null,
    'materiel' => null,
    'tuto' => null,
    'tableau' => null,
    'message' => null,
    'tab_filtres' => null
];

// Logique de traitement
switch ($page) {

//Cours

    case 'cours':
        // Logique pour les cours
        if ($action === 'read') {
            if ($id > 0) {
                $templateData['cours'] = Cours::readOne($id);
            } elseif ($filtre != '') {
                $templateData['tableau'] = Cours::readFiltre($filtre);
                $templateData['tab_filtres'] = Cours::selection_filtre();
                $templateData['message'] = $filtre;
            } else {
                $templateData['tableau'] = Cours::readAll();
                $templateData['tab_filtres'] = Cours::selection_filtre();
            }
        } 
        break;

//Materiel

    case 'materiel':
        // Logique pour les matériels
        if ($action === 'read') {
            if ($id > 0) {
                $templateData['materiel'] = Materiel::readOne($id);
            } elseif ($filtre != '') {
                $templateData['tableau'] = Materiel::readFiltre($filtre);
                $templateData['tab_filtres'] = Materiel::selection_filtre();
                $templateData['message'] = $filtre;
            } else {
                $templateData['tableau'] = Materiel::readAll();
                $templateData['tab_filtres'] = Materiel::selection_filtre();
            }
        } 
        break;

//Tuto

    case 'tuto':
        // Logique pour les tutoriels
        if ($action === 'read') {
            if ($id > 0) {
                $templateData['tuto'] = Tuto::readOne($id);
            } elseif ($filtre != '') {
                $templateData['tableau'] = Tuto::readFiltre($filtre);
                $templateData['tab_filtres'] = Tuto::selection_filtre();
                $templateData['message'] = $filtre;
            } else {
                $templateData['tableau'] = Tuto::readAll();
                $templateData['tab_filtres'] = Tuto::selection_filtre();
            }
        } 
        break;

    case 'erreur':
        $templateData['message'] = 'Cette page n\'existe pas';
        break;

    default:
        $templateData['message'] = 'Page d\'erreur ou page d\'accueil';
}

// Affichage du template
echo $twig->render('page.twig', $templateData);
?>