<?php
// filepath: c:\xampp\htdocs\ttt\Views\frontoffice\logout.php
session_start();

// Détruit toutes les variables de session
$_SESSION = [];

// Détruit la session
session_destroy();

// Redirige l'utilisateur vers la page de connexion
header("Location: login.php");
exit();
?>