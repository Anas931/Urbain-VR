<?php
// filepath: c:\xampp\htdocs\ttt\index.php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ./Views/backoffice/admin-login.php"); // Redirige vers la page de connexion admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index utilisateur</title>

  <!-- Favicons -->
  <link href="./assets/img/favicon.png" rel="icon">
  <link href="./assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="./assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="./assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="./assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="./assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="./assets/css/main.css" rel="stylesheet">
</head>

<body>

<?php
$title = "Liste des utilisateurs";
include './config/database.php'; 
include './views/layout.php';

// Autoload controllers & models (ou require manuels)
include './Controllers/usercontroller.php';
include './models/User.php';

$db = new Database();
$pdo = $db->getConnection();

$userController = new UserController($pdo);

// Routing simple
$action = $_GET['action'] ?? 'users';

switch ($action) {
    case 'users':
        $userController->index();
        break;
    case 'add_user':
        $userController->create();
        break;
    case 'store_user':
        $userController->storeUser();
        break;
    case 'update_user':
        $userController->updateUser();
        break;
    case 'delete_user':
        $userController->deleteUser();
        break;
    case 'read_user':
        $userController->show();
        break;
    case 'activate_user':
        $userController->activateUser();
        break;
    case 'deactivate_user':
        $userController->deactivateUser();
        break;
    default:
        echo "Page introuvable.";
}
?>


<?php include './templates/footer-index.php'; ?>

<script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="./assets/js/main.js"></script>
</body>
</html>


