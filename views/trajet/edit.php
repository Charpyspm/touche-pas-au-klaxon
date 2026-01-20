<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un trajet - Touche pas au klaxon</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-container h2 {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Touche pas au klaxon</div>
        <div class="d-flex align-items-center gap-3">
            <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour aux trajets</a>
            <span class="text-dark fw-medium"><i class="bi bi-person-circle"></i> Bonjour <?php echo htmlspecialchars($userName); ?></span>
            <a href="index.php?action=logout" class="btn btn-dark"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <h2>Modifier le trajet</h2>
            
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <form action="index.php?action=update" method="POST">
                <input type="hidden" name="id" value="<?php echo $trajet['id']; ?>">
                
                <div class="mb-3">
                    <label for="agence_depart_id" class="form-label fw-bold">Agence de départ *</label>
                    <select id="agence_depart_id" name="agence_depart_id" class="form-select" required>
                        <option value="">-- Sélectionnez une agence --</option>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?php echo $agence['id']; ?>" 
                                <?php echo $agence['id'] == $trajet['agence_depart_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($agence['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="agence_arrivee_id" class="form-label fw-bold">Agence d'arrivée *</label>
                    <select id="agence_arrivee_id" name="agence_arrivee_id" class="form-select" required>
                        <option value="">-- Sélectionnez une agence --</option>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?php echo $agence['id']; ?>"
                                <?php echo $agence['id'] == $trajet['agence_arrivee_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($agence['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date_depart" class="form-label fw-bold">Date de départ *</label>
                        <input type="date" id="date_depart" name="date_depart" class="form-control"
                            value="<?php echo date('Y-m-d', strtotime($trajet['date_depart'])); ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="heure_depart" class="form-label fw-bold">Heure de départ *</label>
                        <input type="time" id="heure_depart" name="heure_depart" class="form-control"
                            value="<?php echo date('H:i', strtotime($trajet['date_depart'])); ?>" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date_arrivee" class="form-label fw-bold">Date d'arrivée *</label>
                        <input type="date" id="date_arrivee" name="date_arrivee" class="form-control"
                            value="<?php echo date('Y-m-d', strtotime($trajet['date_arrivee'])); ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="heure_arrivee" class="form-label fw-bold">Heure d'arrivée *</label>
                        <input type="time" id="heure_arrivee" name="heure_arrivee" class="form-control"
                            value="<?php echo date('H:i', strtotime($trajet['date_arrivee'])); ?>" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="nombre_places_total" class="form-label fw-bold">Nombre de places disponibles *</label>
                    <input type="number" id="nombre_places_total" name="nombre_places_total" class="form-control"
                        value="<?php echo $trajet['nombre_places_total']; ?>" min="0" max="5" required>
                </div>
                
                <button type="submit" class="btn btn-dark w-100 mt-3"><i class="bi bi-save"></i> Modifier le trajet</button>
            </form>
        </div>
    </div>

    <footer class="mt-5">
        <p class="text-center text-muted">&copy; <?php echo date('Y'); ?> Touche pas au klaxon - CHARPENTIER Maxence</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
