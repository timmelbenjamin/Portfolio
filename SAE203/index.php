<?php

include('include/twig.php');
$twig = init_twig();

$pdo = new PDO('mysql:host=tp2.iha.unistra.fr;dbname=timmel_sae204;charset=utf8', 'timmel', 'Benji04092005*/');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$requete = "SELECT * FROM film WHERE id BETWEEN 1 AND 30 ORDER BY RAND() LIMIT 5";
$resultat = $pdo->query($requete);
$tableau_films = $resultat->fetchAll(PDO::FETCH_ASSOC);

echo $twig->render('base.twig', [
    'tableau_films' => $tableau_films
]);