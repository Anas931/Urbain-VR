<?php
session_start();
require_once '../config/database.php';
require_once '../models/User.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(strtolower($_POST['email']));

    $db = new Database();
    $conn = $db->getConnection();
    $userModel = new User($conn);
    $user = $userModel->getUserByEmail($email);

    if ($user) {
        // Générer un code de réinitialisation simple (6 chiffres aléatoires)
        $reset_code = rand(100000, 999999);

        // Enregistrer le code dans la BDD (il faudra ajouter un champ `reset_code` et `reset_code_expiration` dans la table user ou créer une table dédiée)
        $stmt = $conn->prepare("UPDATE user SET reset_code = ?, reset_code_expiration = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email = ?");
        $stmt->execute([$reset_code, $email]);

        // Envoyer le mail (simplifié ici)
        $to = $email;
        $subject = "Code de réinitialisation du mot de passe";
        $message = "Votre code de réinitialisation est : " . $reset_code . "\nCe code expire dans 15 minutes.";
        $headers = "From: no-reply@votre-site.com";

        if (mail($to, $subject, $message, $headers)) {
            $success = "Un code de réinitialisation a été envoyé à votre adresse email.";
        } else {
            $error = "Erreur lors de l'envoi de l'email. Veuillez réessayer.";
        }
    } else {
        $error = "Aucun utilisateur trouvé avec cet email.";
    }
}
?>
<?php include '../templates/header-login.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mot de passe oublié</title>
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .forgot-container {
      max-width: 400px;
      margin: auto;
    }
    .form-label {
      font-weight: 600;
    }
  </style>
</head>
<body class="container mt-5">
  <div class="forgot-container">
    <h2 class="text-center mb-4">Mot de passe oublié</h2>

    <?php if ($success): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="bg-light p-4 rounded shadow-sm">
      <div class="mb-3">
        <label for="email" class="form-label text-center d-block">Email</label>
        <input type="email" class="form-control text-center" id="email" name="email" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Envoyer le code</button>
    </form>

    <div class="mt-4 text-center">
      <a href="login.php" class="btn btn-outline-secondary">Retour à la connexion</a>
    </div>
  </div>

  <?php include '../templates/footer-auth.php'; ?>
</body>
</html>
