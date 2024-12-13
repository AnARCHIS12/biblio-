<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Connexion à la base de données (modifier avec vos informations de connexion)
    $host = 'localhost';  // Adresse du serveur MySQL
    $dbname = 'votre_base_de_donnees';  // Nom de la base de données
    $username = 'votre_utilisateur';  // Votre nom d'utilisateur
    $password = 'votre_mot_de_passe';  // Votre mot de passe

    try {
        // Créer la connexion PDO
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer les données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $message = $_POST['message'];
        $genre = $_POST['genre'];
        $preferences = isset($_POST['preferences']) ? implode(', ', $_POST['preferences']) : ''; // Si aucune préférence n'est cochée
        $pays = $_POST['pays'];

        // Préparer la requête SQL pour insérer les données dans la table "region"
        $sql = "INSERT INTO region (nom, prenom, email, message, genre, preferences, pays) 
                VALUES (:nom, :prenom, :email, :message, :genre, :preferences, :pays)";

        $stmt = $pdo->prepare($sql);

        // Lier les paramètres à la requête préparée
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':preferences', $preferences);
        $stmt->bindParam(':pays', $pays);

        // Exécuter la requête
        $stmt->execute();

        // Message de succès
        echo "L'enregistrement a été ajouté avec succès!";
    } catch (PDOException $e) {
        // En cas d'erreur, afficher le message d'erreur
        echo "Erreur de connexion ou d'exécution de la requête: " . $e->getMessage();
    }
}
?>
