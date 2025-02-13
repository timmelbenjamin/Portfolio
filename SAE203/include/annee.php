<?php

// Sélectionne toutes les fresques dans la base de données
function select_artistes($pdo)
{
  // construction de la requête
  $sql = 'select * from fresques';

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