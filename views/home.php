<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Touche pas au klaxon</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        
        .action-icons {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }
        
        .action-icons a {
            text-decoration: none;
            font-size: 18px;
            cursor: pointer;
        }
        
        .icon-view { color: #333; }
        .icon-edit { color: #007bff; }
        .icon-delete { color: #dc3545; }
        
        .icon-view:hover { opacity: 0.7; }
        .icon-edit:hover { opacity: 0.7; }
        .icon-delete:hover { opacity: 0.7; }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        
        .modal.show {
            display: flex;
        }
        
        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            position: relative;
        }
        
        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
            color: #333;
            cursor: pointer;
            background: none;
            border: none;
        }
        
        .modal-close:hover {
            color: #000;
        }
        
        .modal-content h3 {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .modal-info {
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .modal-info strong {
            display: inline-block;
            width: 180px;
        }
        
        .modal-footer {
            text-align: center;
            margin-top: 25px;
        }
        
        .btn-close-modal {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-close-modal:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Touche pas au klaxon</div>
        <?php if ($isConnected): ?>
            <div class="d-flex align-items-center gap-3">
                <a href="index.php?action=create" class="btn btn-secondary"><i class="bi bi-plus-circle"></i> Créer un trajet</a>
                <span class="text-dark fw-medium"><i class="bi bi-person-circle"></i> Bonjour <?php echo htmlspecialchars($userName); ?></span>
                <a href="index.php?action=logout" class="btn btn-dark"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
            </div>
        <?php else: ?>
            <a href="index.php?action=login" class="btn btn-dark"><i class="bi bi-box-arrow-in-right"></i> Connexion</a>
        <?php endif; ?>
    </header>

    <div class="container">
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($isConnected): ?>
            <h2 class="mb-4">Trajets proposés</h2>
        <?php else: ?>
            <p class="text-center mb-4 fs-5">Pour obtenir plus d'informations sur un trajet, veuillez vous connecter</p>
        <?php endif; ?>
        
        <table class="table table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Départ</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Destination</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Places</th>
                    <?php if ($isConnected): ?>
                        <th>Actions</th>
                    <?php endif; ?>
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
                            <?php if ($isConnected): ?>
                                <td>
                                    <div class="action-icons">
                                        <?php if ($trajet['user_id'] == $_SESSION['user_id']): ?>
                                            <a href="index.php?action=edit&id=<?php echo $trajet['id']; ?>" class="icon-edit" title="Modifier">✏️</a>
                                            <a href="index.php?action=delete&id=<?php echo $trajet['id']; ?>" class="icon-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce trajet ?');">🗑️</a>
                                        <?php else: ?>
                                            <a href="#" class="icon-view" data-trajet-id="<?php echo $trajet['id']; ?>" title="Voir les détails">👁️</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?php echo $isConnected ? '8' : '7'; ?>">Aucun trajet disponible pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal pour les détails du trajet -->
    <div id="trajetModal" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">&times;</button>
            <h3>Détails du trajet</h3>
            <div id="modalBody">
                <div class="modal-info">
                    <strong>Auteur :</strong> <span id="modal-auteur"></span>
                </div>
                <div class="modal-info">
                    <strong>Téléphone :</strong> <span id="modal-telephone"></span>
                </div>
                <div class="modal-info">
                    <strong>Email :</strong> <span id="modal-email"></span>
                </div>
                <div class="modal-info">
                    <strong>Nombre total de places :</strong> <span id="modal-places"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-close-modal" onclick="closeModal()">Fermer</button>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour ouvrir la modal
        function openModal(trajetId) {
            fetch('index.php?action=details&id=' + trajetId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('modal-auteur').textContent = data.auteur;
                        document.getElementById('modal-telephone').textContent = data.telephone;
                        document.getElementById('modal-email').textContent = data.email;
                        document.getElementById('modal-places').textContent = data.places_total;
                        
                        document.getElementById('trajetModal').classList.add('show');
                    } else {
                        alert('Erreur: ' + (data.error || 'Impossible de charger les détails'));
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors du chargement des détails');
                });
        }

        // Fonction pour fermer la modal
        function closeModal() {
            document.getElementById('trajetModal').classList.remove('show');
        }

        // Fermer la modal en cliquant en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('trajetModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Attacher les événements aux icônes de vue
        document.addEventListener('DOMContentLoaded', function() {
            const viewIcons = document.querySelectorAll('.icon-view');
            viewIcons.forEach(icon => {
                icon.addEventListener('click', function(e) {
                    e.preventDefault();
                    const trajetId = this.getAttribute('data-trajet-id');
                    if (trajetId) {
                        openModal(trajetId);
                    }
                });
            });
        });
    </script>

    <footer class="mt-5">
        <p class="text-center text-muted">&copy; <?php echo date('Y'); ?> - CENEF - <a href="#">MVC PHP</a></p>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
