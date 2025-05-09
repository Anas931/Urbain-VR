<?php
// Connexion à la base de données
require_once '../../config/database.php';
require_once '../../models/User.php';

$errors = [];
$success = false;
$token = isset($_GET['token']) ? $_GET['token'] : '';

// Si le token n'est pas fourni ou est invalide
if (empty($token)) {
    $errors[] = "Token manquant ou invalide.";
} else {
    $db = new Database();
    $conn = $db->getConnection();
    $userModel = new User($conn);

    // Vérifier que le token existe et n'est pas expiré
    $user = $userModel->getUserByResetToken($token);

    if ($user) {
        if (strtotime($user['reset_expiry']) < time()) {
            // Si le token est expiré
            $errors[] = "Le token a expiré.";
        } else {
            // Token valide et non expiré
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $newPassword = trim($_POST['mdp']);
                if (strlen($newPassword) < 6) {
                    $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
                }

                if (empty($errors)) {
                    // Hash du mot de passe
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $userModel->updatePassword($user['id_user'], $hashedPassword);

                    // Réinitialiser le token et la date d'expiration
                    $userModel->clearResetToken($user['id_user']);

                    $success = true;
                }
            }
        }
    } else {
        $errors[] = "Token invalide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
    <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/main.css" rel="stylesheet">
</head>
<body class="reset-password-page">
<?php include '../../templates/header-loginfront.php'; ?>
    
    <div class="container mt-5">
        <h2 class="text-center mb-4">Réinitialiser votre mot de passe</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                Votre mot de passe a été réinitialisé avec succès. <a href="login.php">Cliquez ici pour vous connecter.</a>
            </div>
        <?php else: ?>
            <form action="" method="post" class="w-50 mx-auto">
                <div class="form-group mb-3">
                    <label for="mdp">Nouveau mot de passe :</label>
                    <input type="password" name="mdp" id="mdp" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Réinitialiser le mot de passe</button>
            </form>
        <?php endif; ?>
    </div>

    <?php include_once '../../templates/footer-views.php'; ?>
</body>
</html>
