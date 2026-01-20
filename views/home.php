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
                <?php if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin']): ?>
                    <a href="#" onclick="openUsersModal(); return false;" class="btn btn-secondary">Utilisateurs</a>
                    <a href="#" onclick="openAgencesModal(); return false;" class="btn btn-secondary">Agences</a>
                    <a href="#" onclick="openTrajetsModal(); return false;" class="btn btn-secondary">Trajets</a>
                <?php else: ?>
                    <a href="index.php?action=create" class="btn btn-secondary"><i class="bi bi-plus-circle"></i> Créer un trajet</a>
                <?php endif; ?>
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
                                        <?php if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin']): ?>
                                            <!-- Admin : seulement supprimer -->
                                            <a href="index.php?action=delete&id=<?php echo $trajet['id']; ?>" class="icon-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce trajet ?');">🗑️</a>
                                        <?php elseif ($trajet['user_id'] == $_SESSION['user_id']): ?>
                                            <!-- Propriétaire : modifier et supprimer -->
                                            <a href="index.php?action=edit&id=<?php echo $trajet['id']; ?>" class="icon-edit" title="Modifier">✏️</a>
                                            <a href="index.php?action=delete&id=<?php echo $trajet['id']; ?>" class="icon-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce trajet ?');">🗑️</a>
                                        <?php else: ?>
                                            <!-- Autres utilisateurs : voir les détails -->
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

    <!-- Modal pour la liste des utilisateurs (admin) -->
    <div id="usersModal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <button class="modal-close" onclick="closeUsersModal()">&times;</button>
            <h3>Liste des utilisateurs</h3>
            <div id="usersModalBody">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody id="users-list">
                        <!-- Chargé dynamiquement -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn-close-modal" onclick="closeUsersModal()">Fermer</button>
            </div>
        </div>
    </div>

    <!-- Modal pour la gestion des agences (admin) -->
    <div id="agencesModal" class="modal">
        <div class="modal-content" style="max-width: 700px;">
            <button class="modal-close" onclick="closeAgencesModal()">&times;</button>
            <h3>Gestion des agences</h3>
            
            <!-- Formulaire d'ajout/modification -->
            <div class="mb-4">
                <form id="agenceForm" class="d-flex gap-2">
                    <input type="hidden" id="agence-id" value="">
                    <input type="text" id="agence-nom" class="form-control" placeholder="Nom de l'agence" required>
                    <button type="submit" class="btn btn-dark" id="agence-submit-btn">
                        <i class="bi bi-plus-circle"></i> Ajouter
                    </button>
                    <button type="button" class="btn btn-secondary" id="agence-cancel-btn" style="display: none;" onclick="cancelAgenceEdit()">
                        <i class="bi bi-x-circle"></i> Annuler
                    </button>
                </form>
            </div>
            
            <div id="agencesModalBody">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Nom de l'agence</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="agences-list">
                        <!-- Chargé dynamiquement -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn-close-modal" onclick="closeAgencesModal()">Fermer</button>
            </div>
        </div>
    </div>

    <!-- Modal pour la gestion des trajets (admin) -->
    <div id="trajetsModal" class="modal">
        <div class="modal-content" style="max-width: 1000px;">
            <button class="modal-close" onclick="closeTrajetsModal()">&times;</button>
            <h3>Gestion des trajets</h3>
            
            <div id="trajetsModalBody">
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
                            <th>Conducteur</th>
                            <th style="width: 80px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="trajets-list">
                        <!-- Chargé dynamiquement -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn-close-modal" onclick="closeTrajetsModal()">Fermer</button>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour ouvrir la modal des utilisateurs
        function openUsersModal() {
            fetch('index.php?action=users')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tbody = document.getElementById('users-list');
                        tbody.innerHTML = '';
                        
                        data.users.forEach(user => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${escapeHtml(user.nom)}</td>
                                <td>${escapeHtml(user.prenom)}</td>
                                <td>${escapeHtml(user.telephone)}</td>
                                <td>${escapeHtml(user.email)}</td>
                            `;
                            tbody.appendChild(tr);
                        });
                        
                        document.getElementById('usersModal').classList.add('show');
                    } else {
                        alert('Erreur: ' + (data.error || 'Impossible de charger la liste'));
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors du chargement de la liste');
                });
        }

        // Fonction pour fermer la modal des utilisateurs
        function closeUsersModal() {
            document.getElementById('usersModal').classList.remove('show');
        }

        // ========== GESTION DES AGENCES ==========
        
        // Ouvrir la modal des agences
        function openAgencesModal() {
            loadAgences();
            document.getElementById('agencesModal').classList.add('show');
        }

        // Fermer la modal des agences
        function closeAgencesModal() {
            document.getElementById('agencesModal').classList.remove('show');
            cancelAgenceEdit();
        }

        // ========== GESTION DES TRAJETS (ADMIN) ==========
        
        // Ouvrir la modal des trajets
        function openTrajetsModal() {
            loadTrajetsAdmin();
            document.getElementById('trajetsModal').classList.add('show');
        }

        // Fermer la modal des trajets
        function closeTrajetsModal() {
            document.getElementById('trajetsModal').classList.remove('show');
        }

        // Charger la liste des trajets pour admin
        function loadTrajetsAdmin() {
            fetch('index.php?action=trajets_admin')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tbody = document.getElementById('trajets-list');
                        tbody.innerHTML = '';
                        
                        if (data.trajets.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="9" class="text-center">Aucun trajet disponible</td></tr>';
                            return;
                        }
                        
                        data.trajets.forEach(trajet => {
                            const dateDepart = new Date(trajet.date_depart);
                            const dateArrivee = new Date(trajet.date_arrivee);
                            
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${escapeHtml(trajet.ville_depart)}</td>
                                <td>${dateDepart.toLocaleDateString('fr-FR')}</td>
                                <td>${dateDepart.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</td>
                                <td>${escapeHtml(trajet.ville_destination)}</td>
                                <td>${dateArrivee.toLocaleDateString('fr-FR')}</td>
                                <td>${dateArrivee.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</td>
                                <td>${trajet.nombre_places_disponibles}</td>
                                <td>${escapeHtml(trajet.conducteur_nom)}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="deleteTrajet(${trajet.id}, '${escapeHtml(trajet.ville_depart)} → ${escapeHtml(trajet.ville_destination)}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });
                    } else {
                        alert('Erreur: ' + (data.error || 'Impossible de charger la liste'));
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors du chargement de la liste');
                });
        }

        // Supprimer un trajet (admin)
        function deleteTrajet(id, description) {
            if (!confirm('Voulez-vous vraiment supprimer le trajet \"' + description + '\" ?')) {
                return;
            }
            
            fetch('index.php?action=delete&id=' + id)
                .then(response => {
                    // Recharger la liste
                    loadTrajetsAdmin();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la suppression');
                });
        }

        // Charger la liste des agences
        function loadAgences() {
            fetch('index.php?action=agences')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tbody = document.getElementById('agences-list');
                        tbody.innerHTML = '';
                        
                        data.agences.forEach(agence => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${escapeHtml(agence.nom)}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editAgence(${agence.id}, '${escapeHtml(agence.nom).replace(/'/g, "\\'")}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteAgence(${agence.id}, '${escapeHtml(agence.nom).replace(/'/g, "\\'")}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });
                    } else {
                        alert('Erreur: ' + (data.error || 'Impossible de charger la liste'));
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors du chargement de la liste');
                });
        }

        // Gérer le formulaire d'agence
        document.addEventListener('DOMContentLoaded', function() {
            const agenceForm = document.getElementById('agenceForm');
            if (agenceForm) {
                agenceForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const id = document.getElementById('agence-id').value;
                    const nom = document.getElementById('agence-nom').value.trim();
                    
                    if (!nom) {
                        alert('Le nom de l\'agence est requis');
                        return;
                    }
                    
                    const action = id ? 'agence_update' : 'agence_create';
                    const formData = new FormData();
                    formData.append('nom', nom);
                    if (id) formData.append('id', id);
                    
                    fetch('index.php?action=' + action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadAgences();
                            cancelAgenceEdit();
                        } else {
                            alert('Erreur: ' + (data.error || 'Opération échouée'));
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Erreur lors de l\'opération');
                    });
                });
            }
        });

        // Éditer une agence
        function editAgence(id, nom) {
            document.getElementById('agence-id').value = id;
            document.getElementById('agence-nom').value = nom;
            document.getElementById('agence-submit-btn').innerHTML = '<i class="bi bi-save"></i> Modifier';
            document.getElementById('agence-cancel-btn').style.display = 'inline-block';
        }

        // Annuler l'édition
        function cancelAgenceEdit() {
            document.getElementById('agence-id').value = '';
            document.getElementById('agence-nom').value = '';
            document.getElementById('agence-submit-btn').innerHTML = '<i class="bi bi-plus-circle"></i> Ajouter';
            document.getElementById('agence-cancel-btn').style.display = 'none';
        }

        // Supprimer une agence
        function deleteAgence(id, nom) {
            if (!confirm('Voulez-vous vraiment supprimer l\'agence \"' + nom + '\" ?')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('id', id);
            
            fetch('index.php?action=agence_delete', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadAgences();
                } else {
                    alert('Erreur: ' + (data.error || 'Impossible de supprimer'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la suppression');
            });
        }

        // Fonction pour échapper le HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

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
            const trajetModal = document.getElementById('trajetModal');
            const usersModal = document.getElementById('usersModal');
            const agencesModal = document.getElementById('agencesModal');
            const trajetsModal = document.getElementById('trajetsModal');
            
            if (event.target === trajetModal) {
                closeModal();
            }
            if (event.target === usersModal) {
                closeUsersModal();
            }
            if (event.target === agencesModal) {
                closeAgencesModal();
            }
            if (event.target === trajetsModal) {
                closeTrajetsModal();
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
