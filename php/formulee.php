<?php

require_once('connexion.php');

$stmt = $connexion->prepare("INSERT INTO utilisateur (nom, prenom, mel, mot_de_passe) VALUES (:nom, :prenom, :mel, :mot_de_passe)");
$stmt->bindParam(':nom', $nom);
$stmt->bindParam(':prenom', $prenom);
$stmt->bindParam(':mel', $mel);
$stmt->bindParam(':mot_de_passe', $mot_de_passe);

 

// insertion d'une ligne

$nom = 'Dupont';
$prenom = 'Paul';
$mel = 'p.dupont@yahoo.fr';
$mot_de_passe = 'secretdupont';
$stmt->execute();
$nb_ligne_affectees = $stmt->rowCount();
echo $nb_ligne_affectees." ligne() insérée(s).<BR>";

 

// insertion d'une autre ligne avec des valeurs différentes

$nom = 'Tremblay';
$prenom = 'Robert';
$mel = 'r.tremblay@gmail.fr';
$mot_de_passe = 'secrettremblay';
$stmt->execute();
$nb_ligne_affectees = $stmt->rowCount();
echo $nb_ligne_affectees." ligne() insérée(s).<BR>";

?>

 