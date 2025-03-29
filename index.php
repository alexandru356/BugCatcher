<?php
/**
 * index.php - Page d'accueil
 * Ce fichier est la page d'accueil des utilisateurs connectés ou non connectés. 
 * Il peuvent naviguer à travers les autres fichiers d'affichage.
 * @author Alexandru Ciuca
 * @date 2025-03-29
 */
require_once __DIR__ . '/lib/fonctions.php';
require_once __DIR__ . '/lib/requetes.php';
require_once __DIR__ . '/includes/init.php';
$succes = $_SESSION['succes'] ?? '';
$erreur = $_SESSION['erreur'] ?? '';
unset($_SESSION['succes'], $_SESSION['erreur']);
//var_dump(est_connecte());

include 'views/partials/_en_tete.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Bienvenue sur <?php echo APP_NAME; ?></h5>
            </div>
            <div class="card-body">
                <h2 class="mb-4">Aidez-nous à améliorer nos logiciels !</h2>
                
                <p class="lead">
                    <?php echo APP_NAME; ?> est une plateforme qui vous permet de signaler les bogues que vous rencontrez 
                    dans nos applications. Vos rapports nous aident à améliorer continuellement la qualité de nos produits.
                </p>

                <?php if ($succes): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($succes) ?></div>
                <?php endif; ?>
                <?php if ($erreur): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
                <?php endif; ?>

                <?php if (est_connecte()): ?>
                    
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h2>Bienvenue, <?= htmlspecialchars($_SESSION['utilisateur']['email']) ?> !</h2>
                    
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4">
                        <a href="<?php echo BASE_URL; ?>/views/bogues/nouveau.php" class="btn btn-primary">
                            Signaler un bogue
                        </a>
                        <a href="<?php echo BASE_URL; ?>/views/bogues/liste.php" class="btn btn-outline-primary">
                            Voir mes rapports
                        </a>
                    </div>
                <?php else: ?>
                    <p class="mt-4">
                        Pour commencer à signaler des bogues, veuillez vous connecter ou créer un compte.
                    </p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a href="<?php echo BASE_URL; ?>/views/enregistrement/connexion.php" class="btn btn-primary">
                            Connexion
                        </a>
                        <a href="<?php echo BASE_URL; ?>/views/enregistrement/inscription.php" class="btn btn-outline-primary">
                            Inscription
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'views/partials/_pied_page.php'; ?>