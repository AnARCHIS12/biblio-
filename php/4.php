<?php
// Connexion à la base de données (modifier avec vos informations de connexion)
$host = 'localhost';  // Adresse du serveur MySQL
$dbname = 'votre_base_de_donnees';  // Nom de la base de données
$username = 'votre_utilisateur';  // Votre nom d'utilisateur
$password = 'votre_mot_de_passe';  // Votre mot de passe

try {
    // Créer la connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Préparer la requête SQL pour lister les régions
    $sql = "SELECT id, nom, prenom, email, message, genre, preferences, pays FROM region";
    $stmt = $pdo->prepare($sql);

    // Exécuter la requête
    $stmt->execute();

    // Vérifier si des résultats sont retournés
    if ($stmt->rowCount() > 0) {
        // Démarrer la table HTML pour afficher les données
        echo '<table border="1" cellpadding="5" cellspacing="0">';
        echo '<tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Message</th><th>Genre</th><th>Préférences</th><th>Pays</th></tr>';

        // Parcourir les résultats et les afficher dans un tableau
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['nom']) . '</td>';
            echo '<td>' . htmlspecialchars($row['prenom']) . '</td>';
            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
            echo '<td>' . htmlspecialchars($row['message']) . '</td>';
            echo '<td>' . htmlspecialchars($row['genre']) . '</td>';
            echo '<td>' . htmlspecialchars($row['preferences']) . '</td>';
            echo '<td>' . htmlspecialchars($row['pays']) . '</td>';
            echo '</tr>';
        }

        // Fermer la table HTML
        echo '</table>';
    } else {
        echo "Aucune région trouvée.";
    }
} catch (PDOException $e) {
    // En cas d'erreur, afficher un message d'erreur
    echo "Erreur : " . $e->getMessage();
}
?>
