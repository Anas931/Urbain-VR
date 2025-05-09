<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Créer un compte - Visites Urbaines</title>

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
<?php include '../../templates/header-loginfront.php'; ?>

<div class="container mt-4">
  <h2 class="text-center mb-4">Créer un compte</h2>

  <?php
  require_once '../../config/database.php';
  require_once '../../models/User.php';

  $errors = [];
  $success = false;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nom = trim($_POST['nom']);
      $prenom = trim($_POST['prenom']);
      $email = trim($_POST['email']);
      $mdp = trim($_POST['mdp']);
      $role = $_POST['role'];

      if (empty($nom)) $errors[] = "Le nom est requis.";
      if (empty($prenom)) $errors[] = "Le prénom est requis.";
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
      if (strlen($mdp) < 6) $errors[] = "Mot de passe trop court (6 caractères min).";
      if (empty($role)) $errors[] = "Le rôle est requis.";

      if (empty($errors)) {
          $db = new Database();
          $conn = $db->getConnection();

          $userModel = new User($conn);
          $userModel->addUser($nom, $prenom, $email, $mdp, $role);

          $success = true;
      }
  }

  if (!empty($errors)) {
      echo '<div class="alert alert-danger"><ul>';
      foreach ($errors as $error) {
          echo '<li>' . htmlspecialchars($error) . '</li>';
      }
      echo '</ul></div>';
  }

  if ($success) {
      echo '<div class="alert alert-success">Compte créé avec succès ! Vous pouvez maintenant vous connecter.</div>';
  }
  ?>

  <form id="addUserForm" action="register.php" method="post" class="w-50 mx-auto">
      <div class="form-group mb-3">
          <label for="nom">Nom :</label>
          <input type="text" name="nom" id="nom" class="form-control" required>
      </div>

      <div class="form-group mb-3">
          <label for="prenom">Prénom :</label>
          <input type="text" name="prenom" id="prenom" class="form-control" required>
      </div>

      <div class="form-group mb-3">
          <label for="email">Adresse email :</label>
          <input type="email" name="email" id="email" class="form-control" required>
      </div>

      <div class="form-group mb-3">
          <label for="mdp">Mot de passe :</label>
          <input type="password" name="mdp" id="mdp" class="form-control" required>
      </div>

      <div class="form-group mb-3">
          <label for="role">Rôle :</label>
          <select name="role" id="role" class="form-select" required>
              <option value="">-- Sélectionnez un rôle --</option>
              <option value="visiteur">Visiteur</option>
              <option value="contributeur">Contributeur</option>
          </select>
      </div>

      <button type="submit" class="btn btn-success w-100">Créer mon compte</button>

      <div class="mt-3 text-center">
          <a href="login.php">Déjà un compte ? Se connecter</a>
      </div>
  </form>
</div>

<?php include_once '../../templates/footer-views.php'; ?>

<!-- Validation JS -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("addUserForm");
    const nomInput = document.getElementById("nom");
    const prenomInput = document.getElementById("prenom");
    const emailInput = document.getElementById("email");
    const mdpInput = document.getElementById("mdp");

    function showFeedback(input, message, isValid) {
        let feedback = input.nextElementSibling;
        if (!feedback || !feedback.classList.contains("form-text")) {
            feedback = document.createElement("div");
            feedback.className = "form-text mt-1";
            input.parentNode.appendChild(feedback);
        }
        feedback.textContent = message;
        feedback.style.color = isValid ? "green" : "red";
        input.classList.remove("is-invalid", "is-valid");
        input.classList.add(isValid ? "is-valid" : "is-invalid");
    }

    function validateNomPrenom(input) {
        const value = input.value.trim();
        const isValid = value.length >= 3 && /^[A-ZÀ-Ÿ]/.test(value);
        if (!isValid) {
            showFeedback(input, "Au moins 3 caractères et commencer par une majuscule.", false);
        } else {
            showFeedback(input, "Champ valide !", true);
        }
        return isValid;
    }

    function validateEmail(input) {
        const value = input.value.trim();
        const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        if (!isValid) {
            showFeedback(input, "Adresse email invalide.", false);
        } else {
            showFeedback(input, "Email valide !", true);
        }
        return isValid;
    }

    function validatePassword(input) {
        const value = input.value;
        const isValid = value.length >= 7;
        if (!isValid) {
            showFeedback(input, "Mot de passe trop court (minimum 7 caractères).", false);
        } else {
            showFeedback(input, "Mot de passe valide !", true);
        }
        return isValid;
    }

    nomInput.addEventListener("input", () => validateNomPrenom(nomInput));
    prenomInput.addEventListener("input", () => validateNomPrenom(prenomInput));
    emailInput.addEventListener("input", () => validateEmail(emailInput));
    mdpInput.addEventListener("input", () => validatePassword(mdpInput));

    form.addEventListener("submit", function (e) {
        const isNomValid = validateNomPrenom(nomInput);
        const isPrenomValid = validateNomPrenom(prenomInput);
        const isEmailValid = validateEmail(emailInput);
        const isMdpValid = validatePassword(mdpInput);

        if (!(isNomValid && isPrenomValid && isEmailValid && isMdpValid)) {
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>
