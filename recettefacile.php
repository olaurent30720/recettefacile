<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recettes faciles</title>
  <meta name="description" content="">
</head>
<body><pre><?php

  // séparer ses identifiants et les protéger, une bonne habitude à prendre
  include "recettefacile-connect.php";

  try {

    // instancie un objet $connexion à partir de la classe PDO
    $connexion = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);

    // Requête de sélection 01
    $requete = "SELECT * FROM `recettes`";
    $prepare = $connexion->prepare($requete);
    $prepare->execute();
    //$resultat = $prepare->fetchAll();

    while ($donnee = $prepare->fetch()){
      echo("<li>".$donnee['recette_titre']."<br>"."<br>".$donnee['recette_contenu']."</li>"."<br>");
  }; // debug & vérification

    // Requête de sélection 02
    $requete = "SELECT *
                FROM `recettes`
                WHERE `recette_id` = :recette_id"; // on cible le podcast dont l'id est ...
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(":recette_id" => 2)); // on cible le podcast dont l'id est 2
    $resultat = $prepare->fetchAll();
    print_r($resultat); // debug & vérification*/

    // Requête d'insertion
    
    $requete = "INSERT INTO `recettes` (`recette_titre`, `recette_contenu`, `recette_datetime`)
                VALUES (:recette_titre, :recette_contenu, :recette_datetime);";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
      ":recette_titre" => "soupe",
      ":recette_contenu" => "choux ",
      ":recette_datetime" => date("Y-m-d H-i")
    ));
    $resultat = $prepare->rowCount(); // rowCount() nécessite PDO::MYSQL_ATTR_FOUND_ROWS => true
    $lastInsertedEpisodeId = $connexion->lastInsertId(); // on récupère l'id automatiquement créé par SQL

    $requete = "INSERT INTO `hashtags` (`hashtag_nom`)
                VALUES (:hashtag_nom);";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
      ":hashtag_nom" => "levain"
    ));
    $resultat = $prepare->rowCount(); // rowCount() nécessite PDO::MYSQL_ATTR_FOUND_ROWS => true
    $lastInsertedEpisodeId = $connexion->lastInsertId();


    //Requête qui lie le hashtag "levain" à la recette du "pain au levain".
    
$requete = "INSERT INTO `assoc_hashtags_recettes`(`assoc_hr_hashtag_id`, `assoc_hr_recette_id`)
VALUES(:hashtagId, :recetteId)";
$prepare = $connexion -> prepare($requete);
$prepare->execute(array(
':hashtagId' => $lastInsertedHashtagId,
'recetteId' => $lastInsertedRecetteId
));

    // Requête de modification
    $requete = "UPDATE `recettes`
                SET `recette_contenu` = :recette_contenu
                WHERE `recette_id` = :recette_id;";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
      ":recette_id"   => 3,
      ":recette_contenu" => "de l'ail et de l'ail😱??"
    ));
    $resultat = $prepare->rowCount();
    
    // Requête de suppression
    $requete = "DELETE FROM `recettes`
                WHERE ((`recette_id` = :recette_id));";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
      ":recette_id"   => 5
    ));
    $resultat = $prepare->rowCount();
    
  } catch (PDOException $e) {

    // en cas d'erreur, on récup et on affiche, grâce à notre try/catch
    exit("❌🙀💀 OOPS :\n" . $e->getMessage());

  }

?></pre></body>
</html>