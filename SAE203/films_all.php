<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include('include/twig.php');
$twig = init_twig();

$pdo = new PDO('mysql:host=tp2.iha.unistra.fr;dbname=timmel_sae204;charset=utf8', 'timmel', 'Benji04092005*/');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$sql = 'select * from theme';
$query = $pdo->prepare($sql);
$query->execute();
$tableau_themes = $query->fetchAll(PDO::FETCH_OBJ);

$sql = 'select film.*, theme.nom as theme, realisateur.nom as realisateur from film
inner join theme on film.id_theme = theme.id
inner join realisateur on film.id_realisateur = realisateur.id';
$query = $pdo->prepare($sql);
$query->execute();
$tableau_films = $query->fetchAll(PDO::FETCH_OBJ);

echo $twig->render('liste_films4.twig', [
'tableau_themes' => $tableau_themes,
'tableau_films' => $tableau_films
]);
