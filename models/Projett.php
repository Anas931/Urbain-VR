<?php

class Projet {
    private $pdo;

    public function __construct() {
        $host = 'localhost';
        $dbname = 'PROJET';
        $user = 'root';
        $pass = '';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    // Récupère tous les projets
    public function getAllProjets() {
        $stmt = $this->pdo->query("SELECT * FROM projet_urbain");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère un projet par ID
    public function getProjetById($id_projet) {
        $sql = "SELECT * FROM projet_urbain WHERE id_projet = :id_projet";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    // Méthode pour ajouter un projet
    public function createProjet($id_user, $nom_projet, $description, $date_debut, $date_fin) {
        $sql = "INSERT INTO projet_urbain (id_user, nom_projet, description, date_debut, date_fin) VALUES (:id_user, :nom_projet, :description, :date_debut, :date_fin)";
        $stmt = $this->pdo->prepare($sql);
    
        return $stmt->execute([
            ':id_user' => $id_user,
            ':nom_projet' => $nom_projet,
            ':description' => $description,
            ':date_debut' => $date_debut,
            ':date_fin' => $date_fin
        ]);
    }

    // Méthode pour mettre à jour un projet
    public function updateProjet($id_projet, $id_user, $nom_projet, $description, $date_debut, $date_fin) {
        $sql = "UPDATE projet_urbain SET id_user = ?, nom_projet = ?, description = ?, date_debut = ?, date_fin = ? WHERE id_projet = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_user, $nom_projet, $description, $date_debut, $date_fin, $id_projet]);
    }

    // Méthode pour supprimer un projet
    public function deleteProjet($id_projet) {
        $stmt = $this->pdo->prepare("DELETE FROM projet_urbain WHERE id_projet = :id_projet");
        $stmt->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function countProjects() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM projet_urbain");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function countDistinctUsers() {
        $query = "SELECT COUNT(DISTINCT id_user) AS total FROM projet_urbain";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}

?>
