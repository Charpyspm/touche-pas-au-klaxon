<?php
$users = [
    ['prenom' => 'Alexandre', 'nom' => 'Martin', 'telephone' => '0612345678', 'email' => 'alexandre.martin@email.fr'],
    ['prenom' => 'Sophie', 'nom' => 'Dubois', 'telephone' => '0698765432', 'email' => 'sophie.dubois@email.fr'],
    ['prenom' => 'Julien', 'nom' => 'Bernard', 'telephone' => '0622446688', 'email' => 'julien.bernard@email.fr'],
    ['prenom' => 'Camille', 'nom' => 'Moreau', 'telephone' => '0611223344', 'email' => 'camille.moreau@email.fr'],
    ['prenom' => 'Lucie', 'nom' => 'Lefèvre', 'telephone' => '0777889900', 'email' => 'lucie.lefevre@email.fr'],
    ['prenom' => 'Thomas', 'nom' => 'Leroy', 'telephone' => '0655443322', 'email' => 'thomas.leroy@email.fr'],
    ['prenom' => 'Chloé', 'nom' => 'Roux', 'telephone' => '0633221199', 'email' => 'chloe.roux@email.fr'],
    ['prenom' => 'Maxime', 'nom' => 'Petit', 'telephone' => '0766778899', 'email' => 'maxime.petit@email.fr'],
    ['prenom' => 'Laura', 'nom' => 'Garnier', 'telephone' => '0688776655', 'email' => 'laura.garnier@email.fr'],
    ['prenom' => 'Antoine', 'nom' => 'Dupuis', 'telephone' => '0744556677', 'email' => 'antoine.dupuis@email.fr'],
    ['prenom' => 'Emma', 'nom' => 'Lefebvre', 'telephone' => '0699887766', 'email' => 'emma.lefebvre@email.fr'],
    ['prenom' => 'Louis', 'nom' => 'Fontaine', 'telephone' => '0655667788', 'email' => 'louis.fontaine@email.fr'],
    ['prenom' => 'Clara', 'nom' => 'Chevalier', 'telephone' => '0788990011', 'email' => 'clara.chevalier@email.fr'],
    ['prenom' => 'Nicolas', 'nom' => 'Robin', 'telephone' => '0644332211', 'email' => 'nicolas.robin@email.fr'],
    ['prenom' => 'Marine', 'nom' => 'Gauthier', 'telephone' => '0677889922', 'email' => 'marine.gauthier@email.fr'],
    ['prenom' => 'Pierre', 'nom' => 'Fournier', 'telephone' => '0722334455', 'email' => 'pierre.fournier@email.fr'],
    ['prenom' => 'Sarah', 'nom' => 'Girard', 'telephone' => '0688665544', 'email' => 'sarah.girard@email.fr'],
    ['prenom' => 'Hugo', 'nom' => 'Lambert', 'telephone' => '0611223366', 'email' => 'hugo.lambert@email.fr'],
    ['prenom' => 'Julie', 'nom' => 'Masson', 'telephone' => '0733445566', 'email' => 'julie.masson@email.fr'],
    ['prenom' => 'Arthur', 'nom' => 'Henry', 'telephone' => '0666554433', 'email' => 'arthur.henry@email.fr']
];


echo "INSERT INTO users (nom, prenom, telephone, email, password, is_admin) VALUES\n";

$inserts = [];
foreach ($users as $user) {
    $password = strtolower(mb_substr($user['prenom'], 0, 1) . mb_substr($user['nom'], 0, 1));
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    $inserts[] = "('" . $user['nom'] . "', '" . $user['prenom'] . "', '" . $user['telephone'] . "', '" . $user['email'] . "', '" . $hash . "', FALSE)";
    
    echo "-- " . $user['prenom'] . " " . $user['nom'] . " -> mot de passe: " . $password . "\n";
}

echo implode(",\n", $inserts) . ";\n";
