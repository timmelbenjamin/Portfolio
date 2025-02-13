<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include('include/twig.php');
$twig = init_twig();

if (isset($_GET['id'])) $id = $_GET['id']; else $id = 0;
$id = intval($id);

$pdo = new PDO('mysql:host=tp2.iha.unistra.fr;dbname=timmel_sae204;charset=utf8', 'timmel', 'Benji04092005*/');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$sql = 'SELECT theme.nom, theme.id, COUNT(DISTINCT film.id) AS film_count FROM theme JOIN film ON theme.id = film.id_theme GROUP BY theme.nom ORDER BY theme.nom;';
$query = $pdo->prepare($sql);
$query->execute();
$tableau_themes = $query->fetchAll(PDO::FETCH_OBJ);

$sql = 'select film.*, theme.nom as theme, realisateur.nom as realisateur from film
inner join theme on film.id_theme = theme.id
inner join realisateur on film.id_realisateur = realisateur.id where film.id_theme = :id';
$query = $pdo->prepare($sql);
$query->bindValue(':id',$id,PDO::PARAM_INT);
$query->execute();
$tableau_films = $query->fetchAll(PDO::FETCH_ASSOC);

echo $twig->render('liste_films_liste.twig', [
'tableau_themes' => $tableau_themes,
'tableau_films' => $tableau_films,
'id' => $id
]);
