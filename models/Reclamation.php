<?php
class Reclamation {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo; // Initialisation de la connexion PDO
    }

    public function getAll() {
        $stmt = $this->pdo->query('SELECT * FROM reclamations ORDER BY date_creation DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM reclamations WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($nom, $email, $message) {
        $stmt = $this->pdo->prepare('INSERT INTO reclamations (nom, email, message, date_creation) VALUES (?, ?, ?, NOW())');
        return $stmt->execute([$nom, $email, $message]);
    }

    public function updateReclamation($id, $nom, $email, $message) {
        $stmt = $this->pdo->prepare('UPDATE reclamations SET nom = ?, email = ?, message = ? WHERE id = ?');
        return $stmt->execute([$nom, $email, $message, $id]);
    }

    public function deleteReclamation($id) {
        $stmt = $this->pdo->prepare('DELETE FROM reclamations WHERE id = ?');
        return $stmt->execute([$id]);
    }
    public function sendEmail($email, $subject, $message) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: malekabdnbei.dadi@esprit.tn" . "\r\n";

    // Send email
    return mail($email, $subject, $message, $headers);
}

    public function index() {
        $model = new Reclamation();
        $reclamations = $model->getAll();
        require_once '../app/views/reclamations/liste.php'; // Charge la vue
    }
    public function getAllSortedByName($order = 'ASC') {
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        $stmt = $this->pdo->prepare("SELECT * FROM reclamations ORDER BY nom $order");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once '../app/views/reclamation/liste_table.php';
        require_once '../app/views/reclamation/liste.php';
    }
    public function getStatsByMessage()
    {
        // Requête pour compter le nombre d'occurrences de chaque message
        $sql = "SELECT message, COUNT(*) as total FROM reclamations GROUP BY message ORDER BY total DESC";
        
        // Exécuter la requête
        $stmt = $this->pdo->query($sql);
        
        // Retourner les résultats sous forme de tableau
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function countReclamations() {
        $query = "SELECT COUNT(*) as total FROM reclamations";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
