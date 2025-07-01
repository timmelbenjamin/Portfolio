<?php
class Tuto {
    public $id;
    public $titre;
    public $image_src;
    public $description;
    public $filtre;
    public $vendeur;
    public $video;
    public $texte;
    public $id_auteur;


    function __construct() {
        // Conversion d'un attribut
        $this->id = intval($this->id);

        // Remplace une valeur vide par autre chose
        if (empty($this->titre)) $this->titre = 'inconnu';
        if (empty($this->image_src)) $this->image_src = 'inconnu';
        if (empty($this->description)) $this->description = 'inconnu';
        if (empty($this->filtre)) $this->filtre = 'inconnu';
        if (empty($this->vendeur)) $this->vendeur = 'inconnu';
        if (empty($this->video)) $this->video = null;
        if (empty($this->texte)) $this->texte = null;
        if (empty($this->id_auteur)) $this->id_auteur = 'inconnu';
    }

    function modifier($titre, $image_src, $description, $filtre, $vendeur, $id_auteur, $video=null, $texte=null) {
        $this->titre = $titre;
        $this->image_src = $image_src;
        $this->description = $description;
        $this->filtre = $filtre;
        $this->vendeur = $vendeur;
        $this->video = $video;
        $this->texte = $texte;
        $this->id_auteur = $id_auteur;
    }

    function afficher() {
        echo '<div class="tuto">';
        echo '<h2>' . $this->titre. '</h2>';
        echo '<img src="' . $this->image_src . '" alt="' . $this->titre . '">';
        echo '<p>' . $this->description . '</p>';
        echo '<p>Filtre: ' . $this->filtre . '</p>';
        echo '<p>Vendeur: ' . $this->vendeur . '</p>';  
        echo '<p>Vidéo: <a href="' . $this->video . '">Regarder ici</a></p>';
        echo '<p>' . $this->texte . '</p>';      
        echo '</div>';
    }

    function affiche_POST() {
        echo '<form action="controleur.php?page=tuto&action=modify" method="POST">';
        echo '<input type="text" name="id" placeholder="' . $this->id . '" required>';
        echo '<input type="text" name="titre" placeholder="' . $this->titre . '" required>';
        echo '<input type="text" name="image_src" placeholder="' . $this->image_src . '" required>';
        echo '<input type="text" name="description" placeholder="' . $this->description . '" required>';
        echo '<input type="text" name="filtre" placeholder="' . $this->filtre . '">';
        echo '<input type="text" name="vendeur" placeholder="' . $this->vendeur . '" required>';
        echo '<input type="text" name="video" placeholder="' . $this->video . '" required>';
        echo '<input type="text" name="texte" placeholder="' . $this->texte . '" required>';
        echo '<input type="text" name="id_auteur" placeholder="' . $this->id_auteur . '" required>';
        echo '<input type="submit" value="Envoyer">';
        echo '</form>';
    }

    static function readAll() {
        $sql = 'SELECT * FROM Tutos';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Tuto');
    }

    static function readOne($id) {
        // Définition de la requête SQL pour récupérer l'article
        $sql = 'SELECT * FROM Tutos WHERE id = :valeur';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':valeur', $id, PDO::PARAM_INT);
        $query->execute();
        $tutos = $query->fetchObject('Tuto');    
        return $tutos; 
    }

    function create() {
        $sql = 'INSERT INTO Tutos (titre, image_src, description, filtre,  vendeur, video, texte, id_auteur) VALUES (:titre, :image_src, :description, :filtre, :vendeur, :video, :texte, :id_auteur);';

        $pdo = connexion();

        $query = $pdo->prepare($sql);
        $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
        $query->bindValue(':image_src', $this->image_src, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':filtre', $this->filtre, PDO::PARAM_STR);
        $query->bindValue(':vendeur', $this->vendeur, PDO::PARAM_STR);
        $query->bindValue(':video', $this->video, PDO::PARAM_STR);
        $query->bindValue(':texte', $this->texte, PDO::PARAM_STR);
        $query->bindValue(':id_auteur', $this->id_auteur, PDO::PARAM_INT);

        $query->execute();
        $this->id = $pdo->lastInsertId();
    }

    static function delete($id) {
        $sql = 'DELETE FROM Tutos WHERE id = :id;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    function update() {
        $sql = 'UPDATE Tutos SET titre = :titre, image_src = :image_src, description = :description, filtre = :filtre, vendeur = :vendeur, video = :video, texte = :texte, id_auteur = :id_auteur WHERE id = :id;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
        $query->bindValue(':image_src', $this->image_src, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':filtre', $this->filtre, PDO::PARAM_STR);
        $query->bindValue(':vendeur', $this->vendeur, PDO::PARAM_STR);
        $query->bindValue(':video', $this->video, PDO::PARAM_STR);
        $query->bindValue(':texte', $this->texte, PDO::PARAM_STR);
        $query->bindValue(':id_auteur', $this->id_auteur, PDO::PARAM_INT);
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
        if (isset($_POST['vendeur'])) {
            $this->vendeur = $_POST['vendeur'];
        }
        if (isset($_POST['video'])) {
            $this->video = $_POST['video'];
        }
        if (isset($_POST['texte'])) {
            $this->texte = $_POST['texte'];
        }
        if (isset($_POST['id'])) {
            $this->id = $_POST['id'];
        }
        if (isset($_POST['id_auteur'])) {
            $this->id_auteur = $_POST['id_auteur'];
        }
    }

    static function selection_filtre() {
        $sql = 'SELECT * FROM Tutos';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute();
        $tableau= $query->fetchAll(PDO::FETCH_CLASS, 'Tuto');
        $liste_filtre = [];
        foreach ($tableau as $tutos){
            if (!in_array($tutos->filtre, $liste_filtre)) {
                $liste_filtre[] = $tutos->filtre; 
            }
        }
        return $liste_filtre; 
    }

    static function readFiltre($filtre) {
        $sql = 'SELECT * FROM Tutos WHERE filtre = :valeur';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':valeur', $filtre, PDO::PARAM_STR);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Tuto');
    }
}