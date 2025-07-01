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
        } elseif ($action === 'create') {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $cours = new Cours();
                $cours->chargePOST();
                if (!empty($cours->titre) && !empty($cours->description)) {
                    $templateData["id_utilisateur"] = $id_utilisateur; 
                    $templateData["role"] = $role; 
                    $cours->create();
                    $templateData['message'] = 'Création d\'un cours';    
                } else {
                    $templateData['message'] = 'Erreur : titre et description doivent être remplis.';
                }
            }
        } elseif ($action === 'delete') {
            Cours::delete($id);
            $templateData['message'] = 'Suppression du cours';
        } elseif ($action === 'modify') {
            $templateData['cours'] = Cours::readOne($id);
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $cours1 = new Cours();
                $cours1->chargePOST();
                if (!empty($cours1->id) && !empty($cours1->titre) && !empty($cours1->description)) {
                    $cours = Cours::readOne($cours1->id);
                    $cours->modifier($cours1->titre, $cours1->image_src, $cours1->description, $cours1->filtre, $cours1->date, $cours1->duree, $cours1->prix, $cours1->vendeur);
                    $cours->update();
                    $templateData['message'] = 'Cours modifié';
                } else {
                    $templateData['message'] = 'Erreur : id, titre et description doivent être remplis.';
                }
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
        } elseif ($action === 'create') {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $materiel = new Materiel();
                $materiel->chargePOST();
                if (!empty($materiel->titre) && !empty($materiel->description)) {
                    $materiel->create();
                    $templateData['message'] = 'Création d\'un matériel';
                } else {
                    $templateData['message'] = 'Erreur : titre et description doivent être remplis.';
                }
            }
        } elseif ($action === 'delete') {
            Materiel::delete($id);
            $templateData['message'] = 'Suppression du matériel';
        } elseif ($action === 'modify') {
            $templateData['materiel'] = Materiel::readOne($id);
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $materiel1 = new Materiel();
                $materiel1->chargePOST();
                if (!empty($materiel1->id) && !empty($materiel1->titre) && !empty($materiel1->description)) {
                    $materiel = Materiel::readOne($materiel1->id);
                    $materiel->modifier($materiel1->titre, $materiel1->image_src, $materiel1->description, $materiel1->filtre, $materiel1->type, $materiel1->prix, $materiel1->vendeur, $materiel1->id_auteur);
                    $materiel->update();
                    $templateData['message'] = 'Matériel modifié';
                } else {
                    $templateData['message'] = 'Erreur : id, titre et description doivent être remplis.';
                }
            }
        }
        break;

//tuto

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
        } elseif ($action === 'create') {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $tuto = new Tuto();
                $tuto->chargePOST();
                if (!empty($tuto->titre) && !empty($tuto->description)) {
                    $tuto->create();
                    $templateData['message'] = 'Création d\'un tutoriel';
                } else {
                    $templateData['message'] = 'Erreur : titre et description doivent être remplis.';
                }
            }
        } elseif ($action === 'delete') {
            Tuto::delete($id);
            $templateData['message'] = 'Suppression du tutoriel';
        } elseif ($action === 'modify') {
            $templateData['tuto'] = Tuto::readOne($id);
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $tuto1 = new Tuto();
                $tuto1->chargePOST();
                if (!empty($tuto1->id) && !empty($tuto1->titre) && !empty($tuto1->description)) {
                    $tuto = Tuto::readOne($tuto1->id);
                    $tuto->modifier($tuto1->titre, $tuto1->image_src, $tuto1->description, $tuto1->filtre, $tuto1->vendeur, $tuto1->id_auteur, $tuto1->video );
                    $tuto->update();
                    $templateData['message'] = 'Tutoriel modifié';
                } else {
                    $templateData['message'] = 'Erreur : id, titre et description doivent être remplis.';
                }
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