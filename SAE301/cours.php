<?php
class Cours {
    public $id;
    public $titre;
    public $image_src;
    public $description;
    public $filtre;
    public $date;
    public $duree;
    public $prix;
    public $vendeur;
    public $id_auteur; 

    function __construct() {
        // Conversion d'un attribut
        $this->id = intval($this->id);
        $this->id_auteur = intval($this->id_auteur); // Initialisation de la nouvelle propriété

        // Remplace une valeur vide par autre chose
        if (empty($this->titre)) $this->titre = 'inconnu';
        if (empty($this->image_src)) $this->image_src = 'inconnu';
        if (empty($this->description)) $this->description = 'inconnu';
        if (empty($this->filtre)) $this->filtre = 'inconnu';
        if (empty($this->date)) $this->date = date('Y-m-d'); // Date actuelle par défaut
        if (empty($this->duree)) $this->duree = '0'; // Durée par défaut
        if (empty($this->prix)) $this->prix = 0;
        if (empty($this->vendeur)) $this->vendeur = 'inconnu';
    }

    function modifier($titre, $image_src, $description, $filtre, $date, $duree, $prix, $vendeur, $id_auteur=null) {
        $this->titre = $titre;
        $this->image_src = $image_src;
        $this->description = $description;
        $this->filtre = $filtre;
        $this->date = $date;
        $this->duree = $duree;
        $this->prix = $prix;
        $this->vendeur = $vendeur;
        if ($id_auteur !== null) {
            $this->id_auteur = $id_auteur; // Mise à jour de la propriété id_auteur
        }
    }

    function afficher() {
        echo '<div class="cours">';
        echo '<h2>' . $this->titre . '</h2>';
        echo '<img src="' . $this->image_src . '" alt="' . $this->titre . '">';
        echo '<p>' . $this->description . '</p>';
        echo '<p>Filtre: ' . $this->filtre . '</p>';
        echo '<p>Date: ' . $this->date . '</p>';
        echo '<p>Durée: ' . $this->duree . ' heures</p>';
        echo '<p>Prix: ' . $this->prix . ' €</p>';
        echo '<p>Vendeur: ' . $this->vendeur . '</p>';
        echo '<p>Auteur ID: ' . $this->id_auteur . '</p>'; // Affichage de l'id_auteur
        echo '</div>';
    }

    function affiche_POST() {
        echo '<form action="controleur.php?page=cours&action=modify" method="POST">';
        echo '<input type="text" name="id" placeholder="' . $this->id . '" required>';
        echo '<input type="text" name="titre" placeholder="' . $this->titre . '" required>';
        echo '<input type="text" name="image_src" placeholder="' . $this->image_src . '" required>';
        echo '<input type="text" name="description" placeholder="' . $this->description . '" required>';
        echo '<input type="text" name="filtre" placeholder="' . $this->filtre . '">';
        echo '<input type="date" name="date" value="' . $this->date . '" required>';
        echo '<input type="text" name="duree" placeholder="' . $this->duree . '" required>';
        echo '<input type="number" name="prix" placeholder="' . $this->prix . '" required>';
        echo '<input type="text" name="vendeur" placeholder="' . $this->vendeur . '" required>';
        echo '<input type="number" name="id_auteur" placeholder="' . $this-> id_auteur . '" required>'; // Ajout du champ pour id_auteur
        echo '<input type="submit" value="Envoyer">';
        echo '</form>';
    }

    static function readAll() {
        $sql = 'SELECT * FROM Cours';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Cours');
    }

    static function readOne($id) {
        $sql = 'SELECT * FROM Cours WHERE id = :valeur';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':valeur', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchObject('Cours');
    }

    function create() {
        $sql = 'INSERT INTO Cours (titre, image_src, description, filtre, date, duree, prix, vendeur, id_auteur) VALUES (:titre, :image_src, :description, :filtre, :date, :duree, :prix, :vendeur, :id_auteur);';

        $pdo = connexion();

        $query = $pdo->prepare($sql);
        $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
        $query->bindValue(':image_src', $this->image_src, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':filtre', $this->filtre, PDO::PARAM_STR);
        $query->bindValue(':date', $this->date, PDO::PARAM_STR);
        $query->bindValue(':duree', $this->duree, PDO::PARAM_STR);
        $query->bindValue(':prix', $this->prix, PDO::PARAM_STR);
        $query->bindValue(':vendeur', $this->vendeur, PDO::PARAM_STR);
        $query->bindValue(':id_auteur', $this->id_auteur, PDO::PARAM_INT); 

        $query->execute();
        $this->id = $pdo->lastInsertId();
    }

    static function delete($id) {
        $sql = 'DELETE FROM Cours WHERE id = :id;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    function update() {
        $sql = 'UPDATE Cours SET titre = :titre, image_src = :image_src, description = :description, filtre = :filtre, date = :date, duree = :duree, prix = :prix, vendeur = :vendeur, id_auteur = :id_auteur WHERE id = :id;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
        $query->bindValue(':image_src', $this->image_src, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':filtre', $this->filtre, PDO::PARAM_STR);
        $query->bindValue(':date', $this->date, PDO::PARAM_STR);
        $query->bindValue(':duree', $this->duree, PDO::PARAM_STR);
        $query->bindValue(':prix', $this->prix, PDO::PARAM_STR);
        $query->bindValue(':vendeur', $this->vendeur, PDO::PARAM_STR);
        $query->bindValue(':id_auteur', $this->id_auteur, PDO::PARAM_INT); // Mise à jour de la propriété

        $query->execute();
    }

    function chargePOST() {
        if (isset($_POST['titre'])) {
            $this->titre = $_POST['titre'];
        }
        if (isset($_POST['image_src'])) {
            $this->image_src = $_POST['image_src'];
        }
        if (isset($_POST['description'])) {
            $this->description = $_POST['description'];
        }
        if (isset($_POST['filtre'])) {
            $this->filtre = $_POST['filtre'];
        }
        if (isset($_POST['date'])) {
            $this->date = $_POST['date'];
        }
        if (isset($_POST['duree'])) {
            $this->duree = $_POST['duree'];
        }
        if (isset($_POST['prix'])) {
            $this->prix = $_POST['prix'];
        }
        if (isset($_POST['vendeur'])) {
            $this->vendeur = $_POST['vendeur'];
        }
        if (isset($_POST['id_auteur'])) {
            $this->id_auteur = $_POST['id_auteur']; 
        }
        if (isset($_POST['id'])) {
            $this->id = $_POST['id'];
        }
    }

    static function selection_filtre() {
        $sql = 'SELECT * FROM Cours';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute();
        $tableau= $query->fetchAll(PDO::FETCH_CLASS, 'Cours');
        $liste_filtre = [];
        foreach ($tableau as $cours){
            if (!in_array($cours->filtre, $liste_filtre)) {
                $liste_filtre[] = $cours->filtre; 
            }
        }
        return $liste_filtre; 
    }

    static function readFiltre($filtre) {
        $sql = 'SELECT * FROM Cours WHERE filtre = :valeur';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':valeur', $filtre, PDO::PARAM_STR);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Cours');
    }
}