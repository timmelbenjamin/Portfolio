<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include('include/twig.php');
$twig = init_twig();

if (isset($_GET['id'])) $id = $_GET['id']; else $id = 0;
$id = intval($id);

$pdo = new PDO('mysql:host=tp2.iha.unistra.fr;dbname=timmel_sae204;charset=utf8', 'timmel', 'Benji04092005*/');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$sql = 'select film.*, theme.nom as theme, realisateur.nom as realisateur from film
inner join theme on film.id_theme = theme.id
inner join realisateur on film.id_realisateur = realisateur.id where film.id=:id';
$query = $pdo->prepare($sql);
$query->bindValue(':id',$id,PDO::PARAM_INT);
$query->execute();
$tableau_films = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = 'select * from commentaires where id_film=:id';
$query = $pdo->prepare($sql);
$query->bindValue(':id',$id,PDO::PARAM_INT);
$query->execute();
$tableau_commentaires = $query->fetchAll(PDO::FETCH_ASSOC);

echo $twig->render('detail_film.twig', [
'tableau_films' => $tableau_films,
'tableau_commentaires' => $tableau_commentaires,
'id' => $id
]);
?>