<?php

// Sélectionne les fresques de la catégorie $id
function select_films_themes($pdo, $id)
{
  // construction de la requête
  $sql = 'select * from film where film.id_theme = :id';

  // exécution de la requête
  $query = $pdo->prepare($sql);
  $query->bindValue(':id',$id,PDO::PARAM_INT);

  $query->execute();

  if ($query->errorCode() == '00000') {
    // récupération des données dans un tableau
    $tableau = $query->fetchAll(PDO::FETCH_OBJ);
  } else {
    echo '<p>Erreur dans la requête : ' . $query->errorInfo()[2] . '</p>';
    $tableau = null;
  }

  return $tableau;
}

// Sélectionne toutes les fresques dans la base de données
function select_films_complet($pdo)
{
  // construction de la requête
  $sql = 'select film.*, theme.nom as theme, realisateur.nom as realisateur from film
    inner join theme on film.id_theme = theme.id
    inner join realisateur on film.id_realisateur = realisateur.id';

  // exécution de la requête
  $query = $pdo->prepare($sql);

  $query->execute();

  if ($query->errorCode() == '00000') {
    // récupération des données dans un tableau
    $tableau = $query->fetchAll(PDO::FETCH_OBJ);
  } else {
    echo '<p>Erreur dans la requête : ' . $query->errorInfo()[2] . '</p>';
    $tableau = null;
  }

  return $tableau;
}