<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Touche pas au klaxon</title>
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Touche pas au klaxon</div>
        <?php if ($isConnected): ?>
            <div style="display: flex; align-items: center; gap: 15px;">
                <a href="creer_trajet.php" class="btn-connexion" style="background-color: #444;">Créer un trajet</a>
                <span style="color: #333; font-weight: 500; font-size: 16px;">Bonjour <?php echo htmlspecialchars($userName); ?></span>
                <a href="logout.php" class="btn-connexion">Déconnexion</a>
            </div>
        <?php else: ?>
            <a href="connexion.php" class="btn-connexion">Connexion</a>
        <?php endif; ?>
    </header>

    <div class="container">
        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($isConnected): ?>
            <h2 style="margin-bottom: 20px;">Trajets proposés</h2>
        <?php else: ?>
            <p class="message">Pour obtenir plus d'informations sur un trajet, veuillez vous connecter</p>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Départ</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Destination</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Places</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($trajets)): ?>
                    <?php foreach ($trajets as $trajet): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($trajet['ville_depart']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($trajet['date_depart'])); ?></td>
                            <td><?php echo date('H:i', strtotime($trajet['date_depart'])); ?></td>
                            <td><?php echo htmlspecialchars($trajet['ville_destination']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($trajet['date_arrivee'])); ?></td>
                            <td><?php echo date('H:i', strtotime($trajet['date_arrivee'])); ?></td>
                            <td><?php echo htmlspecialchars($trajet['nombre_places_disponibles']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Aucun trajet disponible pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; 2024 - CENEF - <a href="#">MVC PHP</a></p>
    </footer>
</body>
</html>
