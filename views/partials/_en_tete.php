<?php
require_once __DIR__ . '/../../includes/init.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <header>
        <!-- Navigation -->
        <?php require_once '_navigation.php'; ?>
    </header>
    <main class="container py-4">
    <!-- A FAIRE: Afficher les messages flash ici -->