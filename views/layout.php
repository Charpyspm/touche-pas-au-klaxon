<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Touche pas au klaxon'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS (compiled from Sass) -->
    <link rel="stylesheet" href="public/css/style.css">
    
    <?php if (isset($additionalStyles)): ?>
        <?php echo $additionalStyles; ?>
    <?php endif; ?>
</head>
<body>
    <header>
        <div class="logo">Touche pas au klaxon</div>
        <?php if (isset($isConnected) && $isConnected): ?>
            <div class="d-flex align-items-center gap-3">
                <a href="index.php?action=create" class="btn btn-secondary">
                    <i class="bi bi-plus-circle"></i> Créer un trajet
                </a>
                <span class="text-dark fw-medium">
                    <i class="bi bi-person-circle"></i> Bonjour <?php echo htmlspecialchars($userName ?? 'Utilisateur'); ?>
                </span>
                <a href="index.php?action=logout" class="btn btn-dark">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            </div>
        <?php else: ?>
            <a href="index.php?action=login" class="btn btn-dark">
                <i class="bi bi-box-arrow-in-right"></i> Connexion
            </a>
        <?php endif; ?>
    </header>

    <main class="container my-4">
        <?php echo $content ?? ''; ?>
    </main>

    <footer class="mt-5">
        <p class="text-center text-muted">
            &copy; <?php echo date('Y'); ?> Touche pas au klaxon - CHARPENTIER Maxence
        </p>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php if (isset($additionalScripts)): ?>
        <?php echo $additionalScripts; ?>
    <?php endif; ?>
</body>
</html>
