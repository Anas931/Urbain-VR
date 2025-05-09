<?php


require_once(__DIR__ . '/../models/User.php');
require_once(__DIR__ . '/../config/config.php');


class UserController {
 
    

    // Affiche tous les utilisateurs
    public function index() {
        $status = $_GET['status'] ?? '';
        $query = "SELECT * FROM user"; // Utilisation de la table `user`
        if ($status === 'active' || $status === 'inactive') {
            $query .= " WHERE status = :status";
        }
        $stmt = $this->pdo->prepare($query);
        if ($status === 'active' || $status === 'inactive') {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include './Views/backoffice/list.php';
    }
    
    
        
    // Affiche le formulaire d'ajout
   
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom']; 
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
            $role = $_POST['role'];

            $userModel = new User();
            $userModel->createUser($nom, $prenom, $email, $mdp, $role); // id_user inutile ici, auto-incrémenté souvent
            header("Location: index.php?action=list");
            exit;
        }
        include '../views/backoffice/add-user.php';
    }
    
    



    

     // Met à jour un utilisateur
     public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usermodel = new User();
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $mdp = !empty($_POST['mdp']) ? password_hash($_POST['mdp'], PASSWORD_DEFAULT) : null;
            $role = $_POST['role'];

            $usermodel->updateUser($id, $nom, $prenom, $email, $mdp, $role);
            header('Location: index.php?action=users');
            exit;
        } else {
            $usermodel = new User();
            $user = $usermodel->getUserById($id);
            include '../views/backoffice/update-user.php';
        }
    }

    
    



   
    // Supprime un utilisateur
    public function delete($id) {
        $usermodel = new User();
        $usermodel->deleteUser($id);
        header('Location: index.php?action=list');
        exit;
    }





 // Affiche les détails d’un utilisateur
 public function show() {
    if (isset($_GET['id'])) {
        $id_user = $_GET['id'];
        $usermodel = new User();
        $user = $usermodel->getUserById($id_user); // Utilise $id_user et pas $id

        if ($user) {
            include '../views/backoffice/read-user.php';
        } else {
            echo "Utilisateur non trouvé.";
        }
    } else {
        echo "ID manquant.";
    }
}
public function showProfile() {
    // Vérifier que l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    // Récupère l'ID de l'utilisateur connecté depuis la session
    $userId = $_SESSION['user_id'];

    // Récupérer les données de l'utilisateur
    $usermodel = new User();
    $userData = $usermodel->getUserById($userId);

    // Vérifie que les données ont été récupérées
    if ($userData) {
        // Passe les données à la vue
        include '../views/frontoffice/profile.php';
    } else {
        echo "Utilisateur non trouvé.";
    }
}




public function store()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        require_once './models/User.php';
        $userModel = new User();
        $userModel->create($nom, $prenom, $email, $mdp, $role);

        header('Location: index.php?action=list');
        exit;
    }
}
// Fonction pour inscription frontoffice
public function register()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $email = trim($_POST['email']);
        $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        $userModel = new User();

        // Vérifier si l'email existe déjà
        $emailExiste = $userModel->emailExiste($email);
        if ($emailExiste) {
            $error_message = "Un compte avec cet email existe déjà.";
            include '../views/frontoffice/register.php';
            exit();
        }

        // Crée le nouvel utilisateur
        $userModel->createUser($nom, $prenom, $email, $mdp, $role);

        // Redirige vers login.php avec succès
        header('Location: ../views/frontoffice/login.php?success=1');
        exit();
    } else {
        // Affiche le formulaire si accès direct (GET)
        include '../views/frontoffice/register.php';
    }
}

public function activateUser() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_user = $_POST['id_user'];

        $query = "UPDATE user SET status = 'active' WHERE id_user = :id_user"; // Table `user`
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Utilisateur activé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de l'activation de l'utilisateur.";
        }
    }
    header("Location: index.php?action=users");
    exit();
}

public function deactivateUser() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_user = $_POST['id_user'];

        $query = "UPDATE user SET status = 'inactive' WHERE id_user = :id_user"; // Table `user`
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Utilisateur désactivé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la désactivation de l'utilisateur.";
        }
    }
    header("Location: index.php?action=users");
    exit();
}

}
if (isset($_GET['action']) && $_GET['action'] === 'login') {
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    // Ex : chargement modèle User.php pour vérifier les identifiants
    require_once '../models/User.php';
    $userModel = new User();

    $user = $userModel->verifierConnexion($email, $mdp);

    if ($user) {
        session_start();
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nom'] = $user['nom'];

        header('Location: ../views/frontoffice/user-dashboard.php');
        exit();
    } else {
        $error_message = "Email ou mot de passe incorrect.";
        include '../views/frontoffice/login.php';
    }
}
