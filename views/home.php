<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Touche pas au klaxon</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header>
        <div class="logo">Touche pas au klaxon</div>
        <a href="connexion.php" class="btn-connexion">Connexion</a>
    </header>

    <div class="container">
        <p class="message">Pour obtenir plus d'informations sur un trajet, veuillez vous connecter</p>
        
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
                            <td><?php echo date('H:i', strtotime($trajet['heure_depart'])); ?></td>
                            <td><?php echo htmlspecialchars($trajet['ville_destination']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($trajet['date_arrivee'])); ?></td>
                            <td><?php echo date('H:i', strtotime($trajet['heure_arrivee'])); ?></td>
                            <td><?php echo htmlspecialchars($trajet['places_disponibles']); ?></td>
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
