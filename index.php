<?php
require_once 'includes/database.php';
require_once 'includes/authentification.php';
require_once 'includes/header.php';
?>

<div class="container py-4">
    <?php displayMessages(); ?>

    <!-- Message en haut -->
    <div class="alert alert-info">
        La Bibliothèque est fermée au public jusqu'à nouvel ordre. Mais il vous est possible de réserver et retirer vos livres via notre service Biblio Drive !
    </div>

    <div class="row">
        <!-- Zone principale -->
        <div class="col-md-8">
            <!-- Barre de recherche -->
            <div class="search-box mb-4">
                <form class="d-flex" action="recherche.php" method="GET">
                    <input type="text" name="q" class="form-control me-2" placeholder="Rechercher un livre...">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </form>
            </div>

            <!-- Liste des livres -->
            <?php
            $query = "SELECT l.*, a.nom as nom_auteur, a.prenom as prenom_auteur 
                     FROM livre l 
                     JOIN auteur a ON l.noauteur = a.noauteur 
                     ORDER BY l.dateajout DESC";
            $stmt = $pdo->query($query);
            while ($livre = $stmt->fetch()) {
                ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($livre['titre']) ?></h5>
                        <h6 class="card-subtitle mb-2">Par <?= htmlspecialchars($livre['prenom_auteur'] . ' ' . $livre['nom_auteur']) ?></h6>
                        <p class="card-text">
                            <small class="text-muted">Année : <?= htmlspecialchars($livre['anneeparution']) ?></small>
                        </p>
                        <?php if (!empty($livre['resume'])): ?>
                            <p class="card-text"><?= htmlspecialchars($livre['resume']) ?></p>
                        <?php endif; ?>
                        <div class="btn-group">
                            <a href="livre.php?id=<?= $livre['nolivre'] ?>" class="btn btn-primary">Voir plus</a>
                            <?php if (isLoggedIn()): ?>
                                <a href="emprunter.php?id=<?= $livre['nolivre'] ?>" class="btn btn-outline-primary">Emprunter</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>

        <!-- Sidebar droite -->
        <div class="col-md-4">
            <?php if (!isLoggedIn()): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Mon compte</h5>
                        <p>Vous devez vous connecter pour accéder à votre compte.</p>
                        <div class="d-grid gap-2">
                            <a href="connexion.php" class="btn btn-primary">Se connecter</a>
                            <a href="inscription.php" class="btn btn-outline-primary">Créer un compte</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Mon compte</h5>
                        <p>Bienvenue, <?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></p>
                        <div class="d-grid gap-2">
                            <a href="mon-compte.php" class="btn btn-primary">Mon profil</a>
                            <a href="mes-emprunts.php" class="btn btn-outline-primary">Mes emprunts</a>
                            <a href="?logout" class="btn btn-outline-danger">Déconnexion</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Panier -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Mon panier</h5>
                    <?php
                    if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0) {
                        echo '<p>' . count($_SESSION['panier']) . ' livre(s) dans votre panier</p>';
                        echo '<a href="panier.php" class="btn btn-primary">Voir mon panier</a>';
                    } else {
                        echo '<p>Votre panier est vide</p>';
                    }
                    ?>
                </div>
            </div>

            <!-- Dernières actualités -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actualités</h5>
                    <div class="news-item mb-3">
                        <h6>Nouveaux horaires</h6>
                        <p class="small">Service Biblio Drive disponible du lundi au vendredi, de 9h à 17h.</p>
                    </div>
                    <div class="news-item">
                        <h6>Nouveautés</h6>
                        <p class="small">Découvrez notre nouvelle collection de science-fiction !</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
