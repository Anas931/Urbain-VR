<?php
// filepath: c:\xampp\htdocs\ttt\Views\frontoffice\profile.php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Récupère les informations de l'utilisateur connecté
$userData = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon Profil</title>

  <!-- Vendor CSS Files -->
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../../assets/css/main.css" rel="stylesheet">

  <style>
    .profile-container {
      max-width: 600px;
      margin: auto;
      padding: 20px;
    }
    .form-label {
      font-weight: 600;
    }
    .profile-picture {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 20px;
    }
  </style>
</head>
<body class="container mt-5">
  <?php include '../../templates/header-front.php'; ?>
  <?php include '../../templates/navbar-front.php'; ?>

  <div class="profile-container">
    <h1 class="text-center mb-4">Mon Profil</h1>

    <!-- Affichage des informations utilisateur -->
    <div class="mb-4 text-center">
      <h2>Informations personnelles</h2>
      <?php if (!empty($userData['photo_profil'])): ?>
        <img src="<?= htmlspecialchars($userData['photo_profil']) ?>" alt="Photo de profil" class="profile-picture">
      <?php else: ?>
        <img src="../../assets/img/default-profile.png" alt="Photo de profil par défaut" class="profile-picture">
      <?php endif; ?>
      <p><strong>Nom :</strong> <?= htmlspecialchars($userData['nom']) ?></p>
      <p><strong>Prénom :</strong> <?= htmlspecialchars($userData['prenom']) ?></p>
      <p><strong>Email :</strong> <?= htmlspecialchars($userData['email']) ?></p>
    </div>

    <hr>

    <!-- Formulaire de mise à jour des informations -->
    <h2>Mettre à jour mes informations</h2>
    <form action="update-profile.php" method="POST" enctype="multipart/form-data" class="mb-4">
      <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($userData['nom']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="prenom" class="form-label">Prénom</label>
        <input type="text" id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars($userData['prenom']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($userData['email']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="photo_profil" class="form-label">Photo de Profil</label>
        <input type="file" id="photo_profil" name="photo_profil" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>

    <hr>

    <!-- Formulaire de changement de mot de passe -->
    <h2>Changer mon mot de passe</h2>
    <form action="update-password.php" method="POST">
      <div class="mb-3">
        <label for="old_password" class="form-label">Mot de passe actuel</label>
        <input type="password" id="old_password" name="old_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="new_password" class="form-label">Nouveau mot de passe</label>
        <input type="password" id="new_password" name="new_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Mettre à jour le mot de passe</button>
    </form>
  </div>

  <?php include '../../templates/footer-auth.php'; ?>

  <!-- Vendor JS Files -->
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
