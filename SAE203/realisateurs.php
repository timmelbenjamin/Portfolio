<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include('include/twig.php');
$twig = init_twig();

$pdo = new PDO('mysql:host=tp2.iha.unistra.fr;dbname=timmel_sae204;charset=utf8', 'timmel', 'Benji04092005*/');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$sql = 'SELECT realisateur.nom, realisateur.id, COUNT(DISTINCT film.id) AS film_count FROM realisateur JOIN film ON realisateur.id = film.id_realisateur GROUP BY realisateur.id ORDER BY realisateur.id;';
$query = $pdo->prepare($sql);
$query->execute();
$tableau_realisateurs = $query->fetchAll(PDO::FETCH_OBJ);

echo $twig->render('realisateurs.twig', [
'tableau_realisateurs' => $tableau_realisateurs
]);