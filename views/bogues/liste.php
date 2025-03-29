<?php
/**
 * liste.php - La liste des bogues correspondant à l'utilisateur connectés
 * Ce fichier permet d'afficher la liste des bogues qui correspondent à l'utilisateur connectés
 * @author Alexandru Ciuca
 * @date 2025-03-29
 */
session_start();
require_once '../../lib/requetes.php';
require_once '../../lib/fonctions.php';
// Vérifier si l'utilisateur est connecté
// A FAIRE: implémenter la redirection si non connecté
// connexion_requise();
if(!est_connecte()){
    header("Location: " . BASE_URL . "/index.php");
    exit();
}
//  A FAIRE: Récupérer l'ID de l'utilisateur connecté
$id_utilisateur = $_SESSION['utilisateur']['id'];
$bogues = [];
// A FAIRE: implémenter la récupération des bogues de l'utilisateur
// 1. Vérifier que $id_utilisateur n'est pas null
// 2. Faire une requête à la base de données pour récupérer tous les bogues de cet utilisateur
// 3. Stocker le résultat dans une variable $bogues
// $bogues = obtenir_bogues_utilisateur($pdo, $id_utilisateur);
if($id_utilisateur != null){
    $bogues = obtenir_bogues_utilisateur($id_utilisateur);
}



include '../partials/_en_tete.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mes bogues signalés</h1>
        <a href="<?php echo BASE_URL; ?>/views/bogues/nouveau.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nouveau bogue
        </a>
    </div>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'created'): ?>
        <div class="alert alert-success">
            Le bogue a été créé avec succès!
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'deleted'): ?>
        <div class="alert alert-success">
            Le bogue a été supprimé avec succès!
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'updated'): ?>
        <div class="alert alert-success">
            Le bogue a été mis à jour avec succès!
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <!-- A FAIRE: implémenter l'affichage de la liste des bogues -->
            <?php if (empty($bogues)): ?>
                <div class="alert alert-info">
                    Vous n'avez pas encore signalé de bogues. 
                    <a href="<?php echo BASE_URL; ?>/views/bogues/nouveau.php">Signalez votre premier bogue</a>.
                </div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($bogues as $bogue): ?>
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><?php echo $bogue['titre']; ?></h5>
                                <p class="mb-1 text-muted">
                                    Créé le <?php echo date('d/m/Y à H:i', strtotime($bogue['date_creation'])); ?>
                                </p>
                                <p class="mb-1">
                                    <?php echo substr($bogue['description'], 0, 100) . (strlen($bogue['description']) > 100 ? '...' : ''); ?>
                                </p>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-<?php echo $bogue['couleur']; ?> me-3">
                                    <?php echo $bogue['statut']; ?>
                                </span>
                                <a href="<?php echo BASE_URL; ?>/views/bogues/details.php?id=<?php echo $bogue['id']; ?>" class="btn btn-sm btn-primary">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../partials/_pied_page.php'; ?>