<?php

include('include/twig.php');
$twig = init_twig();

$pdo = new PDO('mysql:host=tp2.iha.unistra.fr;dbname=timmel_sae204;charset=utf8', 'timmel', 'Benji04092005*/');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

if (isset($_POST['nom']) && isset($_POST['comm']) && !empty($_POST['nom']) && !empty($_POST['comm'])) {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $comm = htmlspecialchars(trim($_POST['comm']));
    $id_film = htmlspecialchars(trim($_POST['id_film']));

    $sql = 'INSERT INTO commentaires (id_film, nom, commentaire) VALUES (:id_film, :nom, :comm)';
    $query = $pdo->prepare($sql);
    $query->bindValue(':id_film', $id_film, PDO::PARAM_INT);
    $query->bindValue(':nom', $nom, PDO::PARAM_STR);
    $query->bindValue(':comm', $comm, PDO::PARAM_STR);

    try {
        $query->execute();
        header('Location: detail.php?id=' . $id_film);
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Erreur : tous les champs ne sont pas remplis.";
}

?>