<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

require_once __DIR__ . '/config/database.php';

// Récupérer toutes les agences pour le formulaire
$database = new Database();
$conn = $database->getConnection();

$queryAgences = "SELECT id, nom FROM agences ORDER BY nom ASC";
$stmt = $conn->prepare($queryAgences);
$stmt->execute();
$agences = $stmt->fetchAll(PDO::FETCH_ASSOC);

$userName = $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'];
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un trajet - Touche pas au klaxon</title>
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
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .btn-submit {
            width: 100%;
            background-color: #333;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        
        .btn-submit:hover {
            background-color: #555;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Touche pas au klaxon</div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="index.php" class="btn-connexion" style="background-color: #444;">Retour aux trajets</a>
            <span style="color: #333; font-weight: 500; font-size: 16px;">Bonjour <?php echo htmlspecialchars($userName); ?></span>
            <a href="logout.php" class="btn-connexion">Déconnexion</a>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <h2>Créer un nouveau trajet</h2>
            
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
            
            <form action="save_trajet.php" method="POST">
                <div class="form-group">
                    <label for="agence_depart_id">Agence de départ *</label>
                    <select id="agence_depart_id" name="agence_depart_id" required>
                        <option value="">-- Sélectionnez une agence --</option>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?php echo $agence['id']; ?>">
                                <?php echo htmlspecialchars($agence['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="agence_arrivee_id">Agence d'arrivée *</label>
                    <select id="agence_arrivee_id" name="agence_arrivee_id" required>
                        <option value="">-- Sélectionnez une agence --</option>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?php echo $agence['id']; ?>">
                                <?php echo htmlspecialchars($agence['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="date_depart">Date de départ *</label>
                        <input type="date" id="date_depart" name="date_depart" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="heure_depart">Heure de départ *</label>
                        <input type="time" id="heure_depart" name="heure_depart" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="date_arrivee">Date d'arrivée *</label>
                        <input type="date" id="date_arrivee" name="date_arrivee" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="heure_arrivee">Heure d'arrivée *</label>
                        <input type="time" id="heure_arrivee" name="heure_arrivee" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="nombre_places_total">Nombre de places disponibles *</label>
                    <input type="number" id="nombre_places_total" name="nombre_places_total" min="1" max="8" required>
                </div>
                
                <button type="submit" class="btn-submit">Créer le trajet</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 - CENEF - <a href="#">MVC PHP</a></p>
    </footer>
</body>
</html>
