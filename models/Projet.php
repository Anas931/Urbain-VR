<?php
/**
 * Classe Projet
 * Modèle pour les projets urbains
 */
class Projet {
    private $pdo;
    private $id;
    private $nom_projet;
    private $description;
    private $date_debut;
    private $date_fin;
    private $statut;
    private $categorie_id;
    private $nom_categorie;

    /**
     * Constructeur
     */
    public function __construct($pdo, $data = []) {
        $this->pdo = $pdo; // Initialisation de la connexion PDO
        $this->hydrate($data);
    }

    /**
     * Hydrate l'objet avec les données
     */
    private function hydrate($data) {
        foreach ($data as $key => $value) {
            $method = 'set' . str_replace('_', '', ucwords($key, '_'));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Getters
     */
    public function getId() {
        return $this->id;
    }

    public function getNomProjet() {
        return $this->nom_projet;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getDateDebut() {
        return $this->date_debut;
    }

    public function getDateFin() {
        return $this->date_fin;
    }

    public function getStatut() {
        return $this->statut;
    }

    public function getCategorieId() {
        return $this->categorie_id;
    }

    public function getNomCategorie() {
        return $this->nom_categorie;
    }

    /**
     * Setters
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setNomProjet($nom_projet) {
        $this->nom_projet = $nom_projet;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setDateDebut($date_debut) {
        $this->date_debut = $date_debut;
        return $this;
    }

    public function setDateFin($date_fin) {
        $this->date_fin = $date_fin;
        return $this;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
        return $this;
    }

    public function setCategorieId($categorie_id) {
        $this->categorie_id = $categorie_id;
        return $this;
    }

    public function setNomCategorie($nom_categorie) {
        $this->nom_categorie = $nom_categorie;
        return $this;
    }

    /**
     * Convertit l'objet en tableau associatif
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'nom_projet' => $this->nom_projet,
            'description' => $this->description,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'statut' => $this->statut,
            'categorie_id' => $this->categorie_id,
            'nom_categorie' => $this->nom_categorie
        ];
    }
    public function countProjects() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM projets");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function countDistinctUsers() {
        $query = "SELECT COUNT(DISTINCT id_user) AS total FROM projets";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}