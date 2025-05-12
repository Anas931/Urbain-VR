<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de l'utilisateur connecté
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de Bord - Utilisateur</title>

  <!-- Vendor CSS Files -->
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../../assets/css/main.css" rel="stylesheet">

  <style>
    .dashboard-container {
      max-width: 800px;
      margin: auto;
      padding: 20px;
    }
    .welcome-message {
      font-size: 1.5rem;
      font-weight: 600;
    }
  </style>
</head>
<body class="container mt-5">
  <div class="dashboard-container">
    <h1 class="text-center mb-4">Bienvenue, <?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?> !</h1>
    <p class="welcome-message text-center">Voici votre tableau de bord.</p>

    <div class="list-group mt-4">
      <a href="profile.php" class="list-group-item list-group-item-action">
        <i class="bi bi-person-circle"></i> Mon Profil
      </a>
      <a href="../../../phpdenada/index.php" class="list-group-item list-group-item-action">
        <i class="bi bi-folder"></i> Mes Projets
      </a>
      <a href="logout.php" class="list-group-item list-group-item-action text-danger">
        <i class="bi bi-box-arrow-right"></i> Déconnexion
      </a>
    </div>
  </div>

  <!-- Footer -->
  <?php include '../../templates/footer-auth.php'; ?>

  <!-- Vendor JS Files -->
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>