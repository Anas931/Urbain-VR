<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/User.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim(strtolower($_POST['email'])), FILTER_VALIDATE_EMAIL);
    $mdp = trim($_POST['mdp']);

    if ($email && $mdp) {
        $db = new Database();
        $conn = $db->getConnection();
        $userModel = new User($conn);

        $user = $userModel->getUserByEmail($email);

        if ($user && password_verify($mdp, $user['mdp'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id_user' => $user['id_user'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            header("Location: dashboarduser.php");
            exit();
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } else {
        $error = "Veuillez saisir des informations valides.";
    }
}
?>
<?php include '../../templates/header-loginfront.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connexion - Visites Urbaines</title>

  <!-- Favicons -->
  <link href="../../assets/img/favicon-urbanisme.png" rel="icon">
  <link href="../../assets/img/apple-touch-icon-urbanisme.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../../assets/css/main.css" rel="stylesheet">

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
        <a href="forgot-password.php">Mot de passe oublié ?</a>
      </div>
    </form>

    <div class="mt-4 text-center">
      <p>Pas encore de compte ?</p>
      <a href="register.php" class="btn btn-outline-secondary">Créer un compte</a>
    </div>
  </div>

  <?php include '../../templates/footer-auth.php'; ?>
</body>
</html>

