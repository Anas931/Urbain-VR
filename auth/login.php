<?php
session_start();
require_once '../config/database.php';
require_once '../models/User.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(strtolower($_POST['email']));
    $mdp = trim($_POST['mdp']);

    $db = new Database();
    $conn = $db->getConnection();
    $userModel = new User($conn);

    $user = $userModel->getUserByEmail($email);

    if ($user) {
        $hashStocke = $user['mdp'];
        
        if (password_verify($mdp, $hashStocke)) {
            $_SESSION['user'] = [
                'id_user' => $user['id_user'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            header("Location: ../views/backoffice/dashboard.php");
            exit();
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>
<?php include '../templates/header-login.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
  <title>Connexion</title>
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .login-container {
      max-width: 400px;
      margin: auto;
    }
    .form-label {
      font-weight: 600;
    }
  </style>
</head>
<body class="container mt-5">
  <div class="login-container">
    <h2 class="text-center mb-4">Connexion</h2>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="post" class="bg-light p-4 rounded shadow-sm">
      <div class="mb-3">
        <label for="email" class="form-label text-center d-block">Email</label>
        <input type="email" class="form-control text-center" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label for="mdp" class="form-label text-center d-block">Mot de passe</label>
        <input type="password" class="form-control text-center" id="mdp" name="mdp" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Se connecter</button>

      <div class="mt-3 text-center">
        <a href="forgot_password.php">Mot de passe oublié ?</a>
      </div>
    </form>

    <div class="mt-4 text-center">
      <p>Pas encore de compte ?</p>
      <a href="ajouter.php" class="btn btn-outline-secondary">Ajouter un utilisateur</a>
    </div>
  </div>
 

  <?php include '../templates/footer-auth.php'; ?>
</body>
</html>

