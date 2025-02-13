<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include('include/twig.php');
$twig = init_twig();

$pdo = new PDO('mysql:host=tp2.iha.unistra.fr;dbname=timmel_sae204;charset=utf8', 'timmel', 'Benji04092005*/');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$sql = 'select * from film order by film.annee';
$query = $pdo->prepare($sql);
$query->execute();
$tableau_films = $query->fetchAll(PDO::FETCH_OBJ);

echo $twig->render('annees.twig', [
'tableau_films' => $tableau_films
]);