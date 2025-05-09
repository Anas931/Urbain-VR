<?php
// filepath: c:\xampp\htdocs\ttt\add-admin.php
require_once '../../config/database.php';
require_once '../../models/User.php';

$db = new Database();
$conn = $db->getConnection();
$userModel = new User($conn);

// Informations de l'administrateur
$nom = 'ANasser';
$prenom = 'Nasser';
$email = 'karamant@gmail.com';
$password = 'khapassword'; // Mot de passe en clair
$hashedPassword = password_hash($password, PASSWORD_DEFAULT); 
$role = 'admin';

// Vérifie si l'email existe déjà
$existingUser = $userModel->getUserByEmail($email);
if ($existingUser) {
    echo "Un utilisateur avec cet email existe déjà.";
    exit();
}

// Ajoute l'administrateur
$query = "INSERT INTO user (nom, prenom, email, mdp, role) VALUES (:nom, :prenom, :email, :mdp, :role)";
$stmt = $conn->prepare($query);
$stmt->bindParam(':nom', $nom);
$stmt->bindParam(':prenom', $prenom);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':mdp', $hashedPassword);
$stmt->bindParam(':role', $role);

if ($stmt->execute()) {
    echo "Administrateur ajouté avec succès.";
} else {
    echo "Erreur lors de l'ajout de l'administrateur.";
}
?>