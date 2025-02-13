<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include('include/twig.php');
$twig = init_twig();

$pdo = new PDO('mysql:host=tp2.iha.unistra.fr;dbname=timmel_sae204;charset=utf8', 'timmel', 'Benji04092005*/');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$sql = 'SELECT theme.nom, theme.id, COUNT(DISTINCT film.id) AS film_count FROM theme JOIN film ON theme.id = film.id_theme GROUP BY theme.nom ORDER BY theme.nom;';
$query = $pdo->prepare($sql);
$query->execute();
$tableau_themes = $query->fetchAll(PDO::FETCH_OBJ);

echo $twig->render('themes.twig', [
'tableau_themes' => $tableau_themes
]);
