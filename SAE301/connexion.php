<?php 

require_once 'vendor/autoload.php'; // Chargez l'autoloader de Composer

// Configuration de Twig
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include('include/connexion.php');
include('cours.php');
include('materiel.php');
include('tuto.php');
include('compte.php');

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
    'message' => null,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $email = trim($_POST['email_connexion']);
        $mot_de_passe = trim($_POST['mot_passe_connexion']);
        if (empty($email) || empty($mot_de_passe)) {
            $templateData["message"] = "Veuillez remplir tous les champs.";
        } else {
            $compte = Compte::readByEmail($email);    
            if ($compte) {
                if ($mot_de_passe == $compte->mot_passe) {
                    $templateData["message"] = "Connexion réussie";
                    $templateData["id_utilisateur"] = $compte->id; 
                    $templateData["role"] = $compte->role; 
                    header("Location: " . $compte->role . "/accueil.php?id_utilisateur=" . $compte->id . "&role=" . $compte->role);
                    exit();
                } else {
                    $templateData["message"] = "Email ou mot de passe incorrect.";
                }
            } else {
                $templateData["message"] = "Aucun compte trouvé avec cet email.";
            }
        }

    } elseif (isset($_POST['action']) && $_POST['action'] === 'register') {
        $emailExists = false; 
        foreach (Compte::readAll() as $compte) {   
            if ($compte->email == $_POST['email']) {
                $emailExists = true; 
                break; 
            }
        }
        if ($emailExists) {
            $templateData["message"] = "Vous avez déjà un compte";
        } else {
            $compte = new Compte();
            $compte->nom = $_POST['nom'];
            $compte->prenom = $_POST['prenom'];
            $compte->email = $_POST['email'];
            $compte->mot_passe = $_POST['mot_passe']; 
            $compte->role = $_POST['role'];
    
            if ($compte->role == 'Professionnel') {
                $compte->RIB = $_POST['RIB'];
            }
            $compte->adresse = $_POST['adresse'];
            $compte->num_telephone = $_POST['num_telephone'];
            $compte->create();
            $templateData["message"] = "Inscription réussie. Vous pouvez vous connecter.";
        }
    }
}

echo $twig->render('connexion_visiteurs.twig', $templateData);
?>