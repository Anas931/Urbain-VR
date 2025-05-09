<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/User.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Récupère les informations de l'utilisateur connecté
$userData = $_SESSION['user'];

// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = trim($_POST['old_password']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Vérifie que tous les champs sont remplis
    if ($oldPassword && $newPassword && $confirmPassword) {
        // Vérifie que le nouveau mot de passe et sa confirmation correspondent
        if ($newPassword === $confirmPassword) {
            $db = new Database();
            $conn = $db->getConnection();
            $userModel = new User($conn);

            // Récupère l'utilisateur depuis la base de données
            $user = $userModel->getUserById($userData['id_user']);

            // Vérifie que l'ancien mot de passe est correct
            if ($user && password_verify($oldPassword, $user['mdp'])) {
                // Hash le nouveau mot de passe
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Met à jour le mot de passe dans la base de données
                $updated = $userModel->updatePassword($userData['id_user'], $hashedPassword);

                if ($updated) {
                    $_SESSION['success'] = "Mot de passe mis à jour avec succès.";
                    header("Location: profile.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Erreur lors de la mise à jour du mot de passe.";
                }
            } else {
                $_SESSION['error'] = "L'ancien mot de passe est incorrect.";
            }
        } else {
            $_SESSION['error'] = "Le nouveau mot de passe et sa confirmation ne correspondent pas.";
        }
    } else {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
    }
    header("Location: profile.php");
    exit();
}
?>
