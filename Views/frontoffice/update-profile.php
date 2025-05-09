<?php
// filepath: c:\xampp\htdocs\ttt\Views\frontoffice\update-profile.php
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
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $photoProfil = $_FILES['photo_profil'];

    if ($nom && $prenom && $email) {
        $db = new Database();
        $conn = $db->getConnection();
        $userModel = new User($conn);

        // Gestion de la photo de profil
        $photoPath = $userData['photo_profil'] ?? null;
        if ($photoProfil['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../uploads/profile_pictures/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Crée le dossier s'il n'existe pas
            }
            $photoPath = $uploadDir . uniqid() . '_' . basename($photoProfil['name']);
            if (!move_uploaded_file($photoProfil['tmp_name'], $photoPath)) {
                $_SESSION['error'] = "Erreur lors du téléchargement de la photo de profil.";
                header("Location: profile.php");
                exit();
            } else {
                // Vérifiez si l'ancien fichier existe et supprimez-le
                if (!empty($userData['photo_profil']) && file_exists($userData['photo_profil'])) {
                    unlink($userData['photo_profil']);
                }
            }
        }

        // Mise à jour des informations utilisateur
        $updated = $userModel->updateUser($userData['id_user'], $nom, $prenom, $email, $photoPath);

        if ($updated) {
            // Met à jour les informations dans la session
            $_SESSION['user']['nom'] = $nom;
            $_SESSION['user']['prenom'] = $prenom;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['photo_profil'] = $photoPath;

            $_SESSION['success'] = "Profil mis à jour avec succès.";
            header("Location: profile.php");
            exit();
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour du profil.";
        }
    } else {
        $_SESSION['error'] = "Veuillez remplir tous les champs correctement.";
    }
    header("Location: profile.php");
    exit();
}
?>