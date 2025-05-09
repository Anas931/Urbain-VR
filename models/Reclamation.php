<?php
class Reclamation {

    private $pdo;

    public function __construct() {
        $host = 'localhost';
        $dbname = 'Projet';
        $user = 'root';  // Par défaut sur XAMPP
        $pass = '';      // Si tu n'as pas de mot de passe pour MySQL

        try {
            $this->pdo = new PDO(
                'mysql:host=' . $host . ';dbname=' . $dbname,
                $user,
                $pass
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }
    // Supprimer une réclamation
     public function deleteReclamation($id) {
    $stmt = $this->pdo->prepare("DELETE FROM reclamation WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}


    // Récupérer toutes les réclamations
    public function getAllReclamations() {
        $stmt = $this->pdo->query("SELECT * FROM reclamation");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer une réclamation par son ID
    public function getReclamationById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM reclamation WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Créer une nouvelle réclamation
    public function createReclamation($nom, $email, $message) {
        $stmt = $this->pdo->prepare("INSERT INTO reclamation (nom, email, message) VALUES (:nom, :email, :message)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);
        return $stmt->execute();
    }

   // Mettre à jour une réclamation
public function updateReclamation($id, $nom, $email, $message) {
    $stmt = $this->pdo->prepare("UPDATE reclamation SET nom = :nom, email = :email, message = :message WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);
    return $stmt->execute();
}
public function countReclamations() {
    $query = "SELECT COUNT(*) as total FROM reclamation";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}


}