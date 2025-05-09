<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Ajouter un utilisateur</title>

  <!-- Favicons -->
  <link href="../../assets/img/favicon.png" rel="icon">
  <link href="../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../../assets/css/main.css" rel="stylesheet">
</head>

<body>
<?php include '../../templates/header-user.php'; ?>
<?php include '../../templates/navbar-views.php'; ?>


<?php
require_once(__DIR__ . '/../../models/User.php');
require_once(__DIR__ . '/../../config/database.php');

$usermodel = new User();
$users = $usermodel->getAllUsers();
?>

<div class="container mt-4">
    <h2>Liste des utilisateurs</h2>

    <!-- Formulaire de filtrage -->
    <form method="GET" action="index.php" class="mb-4">
      <input type="hidden" name="action" value="users">
      <label for="status" class="form-label">Filtrer par statut :</label>
      <select name="status" id="status" class="form-select" onchange="this.form.submit()">
        <option value="">Tous</option>
        <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>Actifs</option>
        <option value="inactive" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactifs</option>
      </select>
    </form>

    <?php if ($users && count($users) > 0): ?>
        <!-- Tableau des utilisateurs -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                  <td><?= htmlspecialchars($user['nom']) ?></td>
                  <td><?= htmlspecialchars($user['prenom']) ?></td>
                  <td><?= htmlspecialchars($user['email']) ?></td>
                  <td><?= htmlspecialchars($user['role']) ?></td>
                  <td><?= htmlspecialchars($user['status']) === 'active' ? 'Actif' : 'Inactif' ?></td>
                  <td>
                    <!-- Boutons pour activer/désactiver -->
                    <?php if ($user['status'] === 'active'): ?>
                      <form action="/ttt/index.php?action=deactivate_user" method="POST" style="display:inline;">
                        <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
                        <button type="submit" class="btn btn-warning btn-sm">Désactiver</button>
                      </form>
                    <?php else: ?>
                      <form action="/ttt/index.php?action=activate_user" method="POST" style="display:inline;">
                        <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
                        <button type="submit" class="btn btn-success btn-sm">Activer</button>
                      </form>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun utilisateur trouvé.</p>
    <?php endif; ?>
</div>

<?php include '../../templates/footer-views.php'; ?>
<!-- Vendor JS Files -->
<script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/vendor/aos/aos.js"></script>

<!-- Main JS File -->
<script src="../../assets/js/main-js.js"></script>
    </body>
</html>