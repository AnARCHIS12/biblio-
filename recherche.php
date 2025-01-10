<?php
require_once 'database.php';
require_once 'includes/header.php';
requireLogin();

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer les paramètres de recherche
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$categorie = isset($_GET['categorie']) ? trim($_GET['categorie']) : '';
$annee_min = isset($_GET['annee_min']) ? filter_var($_GET['annee_min'], FILTER_VALIDATE_INT) : null;
$annee_max = isset($_GET['annee_max']) ? filter_var($_GET['annee_max'], FILTER_VALIDATE_INT) : null;
$disponibilite = isset($_GET['disponibilite']) ? $_GET['disponibilite'] : 'tous';

try {
    // Requête de base
    $sql = "SELECT l.*, a.nom as auteur_nom, a.prenom as auteur_prenom 
            FROM livre l 
            LEFT JOIN auteur a ON l.noauteur = a.noauteur 
            WHERE 1=1";
    $params = [];

    // Ajouter les conditions
    if (!empty($q)) {
        $sql .= " AND (l.titre LIKE ? OR l.resume LIKE ?)";
        $searchTerm = "%$q%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    if (!empty($categorie)) {
        $sql .= " AND l.categorie = ?";
        $params[] = $categorie;
    }

    // Ordre par date d'ajout
    $sql .= " ORDER BY l.dateajout DESC";

    // Exécuter la requête
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $resultats = $stmt->fetchAll();

    // Récupérer les catégories pour le filtre
    $stmt_categories = $pdo->query("SELECT DISTINCT categorie FROM livre WHERE categorie IS NOT NULL ORDER BY categorie");
    $categories = $stmt_categories->fetchAll(PDO::FETCH_COLUMN);

    // Debug
    echo "<!-- Requête SQL : $sql -->";
    echo "<!-- Paramètres : " . implode(", ", $params) . " -->";
    echo "<!-- Nombre de résultats : " . count($resultats) . " -->";
    if (!empty($resultats)) {
        echo "<!-- Premier résultat : ";
        var_export($resultats[0]);
        echo " -->";
    }

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erreur lors de la recherche : " . $e->getMessage();
    $resultats = [];
    $categories = [];
}
?>

<div class="container py-5">
    <!-- Formulaire de recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <!-- Recherche principale -->
                <div class="col-12">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" 
                               placeholder="Rechercher par titre..." 
                               value="<?php echo htmlspecialchars($q); ?>">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                    </div>
                </div>

                <!-- Catégorie -->
                <div class="col-md-4">
                    <label for="categorie" class="form-label">Catégorie</label>
                    <select class="form-select" id="categorie" name="categorie">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>"
                                    <?php echo $categorie === $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Résultats -->
    <?php if (empty($resultats)): ?>
        <div class="alert alert-info">
            Aucun livre ne correspond à votre recherche.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($resultats as $livre): ?>
                <div class="col">
                    <div class="card h-100">
                        <?php if ($livre['image']): ?>
                            <img src="<?php echo htmlspecialchars($livre['image']); ?>" 
                                 class="card-img-top" alt="Couverture">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($livre['titre']); ?></h5>
                            <p class="card-text">
                                <?php if ($livre['auteur_prenom'] || $livre['auteur_nom']): ?>
                                    Par <?php echo htmlspecialchars($livre['auteur_prenom'] . ' ' . $livre['auteur_nom']); ?><br>
                                <?php endif; ?>
                                Catégorie : <?php echo htmlspecialchars($livre['categorie']); ?>
                            </p>
                            <a href="livre.php?id=<?php echo $livre['nolivre']; ?>" 
                               class="btn btn-primary">Voir plus</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
