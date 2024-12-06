<?php
require_once '../includes/header.php';
requireAdmin(); // S'assure que seuls les administrateurs peuvent accéder à cette page

// Créer le dossier des couvertures s'il n'existe pas
$upload_dir = "../assets/images/covers/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Traitement du formulaire d'ajout de livre
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_STRING);
    $anneeparution = filter_input(INPUT_POST, 'anneeparution', FILTER_SANITIZE_NUMBER_INT);
    $resume = filter_input(INPUT_POST, 'resume', FILTER_SANITIZE_STRING);
    $auteur_nom = filter_input(INPUT_POST, 'auteur_nom', FILTER_SANITIZE_STRING);
    $auteur_prenom = filter_input(INPUT_POST, 'auteur_prenom', FILTER_SANITIZE_STRING);
    $image_path = null;

    // Traitement de l'upload d'image
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['cover']['tmp_name'];
        $file_name = $_FILES['cover']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Vérifier le type de fichier
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_ext, $allowed)) {
            // Générer un nom unique pour éviter les conflits
            $new_name = uniqid() . '.' . $file_ext;
            $destination = $upload_dir . $new_name;
            
            if (move_uploaded_file($file_tmp, $destination)) {
                $image_path = 'assets/images/covers/' . $new_name;
            } else {
                $_SESSION['error_message'] = "Erreur lors de l'upload de l'image.";
            }
        } else {
            $_SESSION['error_message'] = "Type de fichier non autorisé. Utilisez JPG, PNG ou GIF.";
        }
    }

    try {
        // Commencer une transaction
        $pdo->beginTransaction();

        // Vérifier si l'auteur existe déjà
        $stmt = $pdo->prepare("SELECT noauteur FROM auteur WHERE nom = ? AND prenom = ?");
        $stmt->execute([$auteur_nom, $auteur_prenom]);
        $auteur = $stmt->fetch();

        if ($auteur) {
            $noauteur = $auteur['noauteur'];
        } else {
            // Créer un nouvel auteur
            $stmt = $pdo->prepare("INSERT INTO auteur (nom, prenom) VALUES (?, ?)");
            $stmt->execute([$auteur_nom, $auteur_prenom]);
            $noauteur = $pdo->lastInsertId();
        }

        // Ajouter le livre avec l'image
        $stmt = $pdo->prepare("INSERT INTO livre (titre, anneeparution, resume, noauteur, image) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$titre, $anneeparution, $resume, $noauteur, $image_path])) {
            // Valider la transaction
            $pdo->commit();
            $_SESSION['success_message'] = "Le livre a été ajouté avec succès !";
            header('Location: ajouter-livre.php');
            exit();
        }
    } catch(PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        $_SESSION['error_message'] = "Erreur lors de l'ajout du livre : " . $e->getMessage();
    }
}
?>

<div class="container py-4">
    <h1 class="mb-4">Ajouter un livre</h1>

    <?php displayMessages(); ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre du livre</label>
                    <input type="text" class="form-control" id="titre" name="titre" required>
                </div>

                <div class="mb-3">
                    <label for="anneeparution" class="form-label">Année de parution</label>
                    <input type="number" class="form-control" id="anneeparution" name="anneeparution" required>
                </div>

                <div class="mb-3">
                    <label for="resume" class="form-label">Résumé</label>
                    <textarea class="form-control" id="resume" name="resume" rows="4"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="auteur_nom" class="form-label">Nom de l'auteur</label>
                            <input type="text" class="form-control" id="auteur_nom" name="auteur_nom" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="auteur_prenom" class="form-label">Prénom de l'auteur</label>
                            <input type="text" class="form-control" id="auteur_prenom" name="auteur_prenom" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="cover" class="form-label">Image de couverture</label>
                    <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
                    <div class="form-text">Formats acceptés : JPG, PNG, GIF. Taille maximale : 2 Mo</div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Ajouter le livre</button>
                    <a href="../index.php" class="btn btn-outline-secondary">Retour à l'accueil</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
