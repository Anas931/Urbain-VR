<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mot de passe oublié - Visites Urbaines</title>

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
</head>

<body class="login-page">
<?php include_once '../../templates/header-front.php'; ?>
<?php include_once '../../templates/navbar-front.php'; ?>

<div class="container mt-4">
  <h2 class="text-center mb-4">Mot de passe oublié</h2>

  <?php
  require_once '../../config/database.php';
  require_once '../../models/User.php';

  $message = "";

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = trim($_POST['email']);

      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $db = new Database();
          $conn = $db->getConnection();

          $userModel = new User($conn);
          $user = $userModel->findUserByEmail($email);

          if ($user) {
              // Générer un token de réinitialisation
              $token = bin2hex(random_bytes(32));
              $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

              // Sauvegarder le token dans la base (à toi de créer la colonne `reset_token`, `reset_expiry` si besoin)
              $userModel->setResetToken($email, $token, $expiry);

              // (Optionnel) Envoyer un email avec le lien (on le simulera ici)
              $resetLink = "http://localhost/views/frontoffice/reset-password.php?token=$token";
              // TODO : envoyer $resetLink par email

              // On n'indique pas si l'email existe ou non → pour la sécurité
              $message = '<div class="alert alert-success">Si cet email existe, un lien de réinitialisation vous a été envoyé.</div>';
          } else {
              // Même message pour éviter l'énumération d'email
              $message = '<div class="alert alert-success">Si cet email existe, un lien de réinitialisation vous a été envoyé.</div>';
          }
      } else {
          $message = '<div class="alert alert-danger">Veuillez saisir une adresse email valide.</div>';
      }
  }

  echo $message;
  ?>

  <form id="forgotPasswordForm" action="forgot-password.php" method="post" class="w-50 mx-auto">
      <div class="form-group mb-3">
          <label for="email">Adresse email :</label>
          <input type="email" name="email" id="email" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary w-100">Envoyer le lien de réinitialisation</button>

      <div class="mt-3 text-center">
          <a href="login.php">Retour à la connexion</a>
      </div>
  </form>
</div>

<?php include_once '../../templates/footer-views.php'; ?>

<!-- Validation JS -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("forgotPasswordForm");
    const emailInput = document.getElementById("email");

    function validateEmail(input) {
        const value = input.value.trim();
        const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        input.classList.remove("is-invalid", "is-valid");
        if (isValid) {
            input.classList.add("is-valid");
        } else {
            input.classList.add("is-invalid");
        }
        return isValid;
    }

    emailInput.addEventListener("input", () => validateEmail(emailInput));

    form.addEventListener("submit", function (e) {
        if (!validateEmail(emailInput)) {
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>
