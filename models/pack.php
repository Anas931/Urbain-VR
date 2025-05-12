<?php

class Pack {
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

    // Récupère tous les packs
    public function getAllPacks() {
        $stmt = $this->pdo->query("SELECT * FROM pack");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère un pack par ID
    public function getPackById($id_pack) {
        $sql = "SELECT * FROM pack WHERE id_pack = :id_pack";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_pack', $id_pack, PDO::PARAM_INT);
        $stmt->execute();

        // Vérifie si le pack existe
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;  // Si aucun pack n'est trouvé
        }
    }

    // Ajoute un pack
    public function addPack($id_user, $id_abon, $prix) {
        $sql = "INSERT INTO pack (id_user, id_abon, prix) VALUES (:id_user, :id_abon, :prix)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id_user' => $id_user,
            ':id_abon' => $id_abon,
            ':prix' => $prix
        ]);
    }

    // Met à jour un pack
    public function updatePack($id_pack, $id_user, $id_abon, $prix) {
        $stmt = $this->pdo->prepare("UPDATE pack SET id_user = :id_user, id_abon = :id_abon, prix = :prix WHERE id_pack = :id_pack");
        $stmt->bindParam(':id_pack', $id_pack);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':id_abon', $id_abon);
        $stmt->bindParam(':prix', $prix);
        return $stmt->execute();
    }

    // Supprime un pack
    public function deletePack($id_pack) {
        $stmt = $this->pdo->prepare("DELETE FROM pack WHERE id_pack = :id_pack");
        $stmt->bindParam(':id_pack', $id_pack, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function countPacks()
    {
        $query = "SELECT COUNT(*) as total FROM pack";
        $stmt = $this->pdo->prepare($query); // ❗ Assure-toi que $this->conn est bien défini
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    pu
    
}
