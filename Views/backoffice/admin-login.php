<?php
// filepath: c:\xampp\htdocs\ttt\Views\backoffice\admin-login.php
session_start();
require_once '../../config/database.php';
require_once '../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $db = new Database();
    $conn = $db->getConnection();
    $userModel = new User($conn);

    // Vérifie si l'utilisateur existe dans la base de données
    $user = $userModel->getUserByEmail($username);

    if ($user && $user['role'] === 'admin' && password_verify($password, $user['mdp'])) {
        // Authentification réussie
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin'] = [
            'id_user' => $user['id_user'],
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'email' => $user['email']
        ];
        header("Location: ../../index.php"); // Redirige vers le tableau de bord du backoffice
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion Admin - Backoffice</title>
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h1 class="text-center">Connexion Admin</h1>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="POST" class="mt-4">
    <div class="mb-3">
      <label for="username" class="form-label">Nom d'utilisateur</label>
      <input type="text" id="username" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Mot de passe</label>
      <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
  </form>
</body>
</html>