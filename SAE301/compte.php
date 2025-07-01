<?php
class Compte {
    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $mot_passe;
    public $role;
    public $RIB;
    public $adresse;
    public $num_telephone;

    function __construct() {
        $this->id = intval($this->id);

        if (empty($this->nom)) $this->nom = 'inconnu';
        if (empty($this->prenom)) $this->prenom = 'inconnu';
        if (empty($this->email)) $this->email = 'inconnu';
        if (empty($this->mot_passe)) $this->mot_passe = 'inconnu';
        if (empty($this->role)) $this->role = 'inconnu';
        if (empty($this->RIB)) $this->RIB = 'inconnu';
        if (empty($this->adresse)) $this->adresse = 'inconnu';
        if (empty($this->num_telephone)) $this->num_telephone = 'inconnu';
    }

    function modifier($nom, $prenom, $email, $mot_passe, $role, $RIB, $adresse, $num_telephone) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->mot_passe = $mot_passe;
        $this->role = $role;
        $this->RIB = $RIB;
        $this->adresse = $adresse;
        $this->num_telephone = $num_telephone;
    }

    function afficher_visiteurs() {
        echo '<p>Nom: ' . $this->nom . '</p>';
        echo '<p>Prénom: ' . $this->prenom . '</p>';
        echo '<p>Email: ' . $this->email . '</p>';
        echo '<p>Rôle: ' . $this->role . '</p>';
        echo '<p>RIB: ' . $this->RIB . '</p>';
        echo '<p>Adresse: ' . $this->adresse . '</p>';
        echo '<p>Numéro de téléphone: ' . $this->num_telephone . '</p>';
    }

    function affiche_POST() {
        echo '<form action="controleur.php?page=compte&action=modify" method="POST">';
        echo '<input type="text" name="id" placeholder="'.$this->id.'">';
        echo '<input type="text" name="nom" placeholder="'.$this->nom.'">';
        echo '<input type="text" name="prenom" placeholder="'.$this->prenom.'">';
        echo '<input type="email" name="email" placeholder="'.$this->email.'">';
        echo '<input type="password" name="mot_passe" placeholder="'.$this->mot_passe.'">';
        echo '<input type="text" name="role" placeholder="'.$this->role.'">';
        echo '<input type="text" name="RIB" placeholder="'.$this->RIB.'">';
        echo '<input type="text" name="adresse" placeholder="'.$this->adresse.'">';
        echo '<input type="text" name="num_telephone" placeholder="'.$this->num_telephone.'">';
        echo '<input type="submit" value="Envoyer">';
        echo '</form>';
    }

    static function readAll() {
        $sql = 'SELECT * FROM Compte';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Compte');
    }

    static function readOne($id) {
        $sql = 'SELECT * FROM Compte WHERE id = :valeur';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':valeur', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchObject('Compte');
    }

    static function readByEmail($email) {
        $sql = 'SELECT * FROM Compte WHERE email = :email';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        return $query->fetchObject('Compte');
    }

    function create() {
        $sql = 'INSERT INTO Compte (nom, prenom, email, mot_passe, role, RIB , adresse, num_telephone) VALUES (:nom, :prenom, :email, :mot_passe, :role, :RIB, :adresse, :num_telephone);';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $query->bindValue(':prenom', $this->prenom, PDO::PARAM_STR);
        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':mot_passe', $this->mot_passe, PDO::PARAM_STR);
        $query->bindValue(':role', $this->role, PDO::PARAM_STR);
        $query->bindValue(':RIB', $this->RIB, PDO::PARAM_STR);
        $query->bindValue(':adresse', $this->adresse, PDO::PARAM_STR);
        $query->bindValue(':num_telephone', $this->num_telephone, PDO::PARAM_STR);
        $query->execute();
        $this->id = $pdo->lastInsertId();
    }

    static function delete($id) {
        $sql = 'DELETE FROM Compte WHERE id = :id;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    function update() {
        $sql = 'UPDATE Compte SET nom = :nom, prenom = :prenom, email = :email, mot_passe = :mot_passe, role = :role, RIB = :RIB, adresse = :adresse, num_telephone = :num_telephone WHERE id = :id;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $query->bindValue(':prenom', $this->prenom, PDO::PARAM_STR);
        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':mot_passe', $this->mot_passe, PDO::PARAM_STR);
        $query->bindValue(':role', $this->role, PDO::PARAM_STR);
        $query->bindValue(':RIB', $this->RIB, PDO::PARAM_STR);
        $query->bindValue(':adresse', $this->adresse, PDO::PARAM_STR);
        $query->bindValue(':num_telephone', $this->num_telephone, PDO::PARAM_STR);
        $query->execute();
    }

    function chargePOST() {
        if (isset($_POST['nom'])) {
            $this->nom = $_POST['nom'];
        } else {
            $this->nom = '';
        }
        if (isset($_POST['prenom'])) {
            $this->prenom = $_POST['prenom'];
        } else {
            $this->prenom = '';
        }
        if (isset($_POST['email'])) {
            $this->email = $_POST['email'];
        } else {
            $this->email = '';
        }
        if (isset($_POST['mot_passe'])) {
            $this->mot_passe = $_POST['mot_passe'];
        } else {
            $this->mot_passe = '';
        }
        if (isset($_POST['role'])) {
            $this->role = $_POST['role'];
        } else {
            $this->role = '';
        }
        if (isset($_POST['RIB'])) {
            $this->RIB = $_POST['RIB'];
        } else {
            $this->RIB = '';
        }
        if (isset($_POST['adresse'])) {
            $this->adresse = $_POST['adresse'];
        } else {
            $this->adresse = '';
        }
        if (isset($_POST['num_telephone'])) {
            $this->num_telephone = $_POST['num_telephone'];
        } else {
            $this->num_telephone = '';
        }
    }
}

?>