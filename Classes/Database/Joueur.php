<?php
namespace Database;

class Joueur {
    private $name;
    private $score;

    public function __construct($name, $score = 0) {
        $this->name = $name;
        $this->score = $score;
    }

    public function getName() {
        return $this->name;
    }

    public function getScore() {
        return $this->score;
    }

    public function setName($name) {
        // Vérifier si le pseudo existe déjà
        $pdo = Connection::get();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM scores WHERE pseudo = :pseudo");
        $stmt->execute([':pseudo' => $name]);
        if ($stmt->fetchColumn() > 0 && $name !== $this->name) {
            throw new \Exception("Le pseudo '$name' est déjà utilisé.");
        }

        // Mettre à jour en BD
        $stmt = $pdo->prepare("UPDATE scores SET pseudo = :newName WHERE pseudo = :oldName");
        $stmt->execute([
            ':newName' => $name,
            ':oldName' => $this->name
        ]);

        $this->name = $name;
    }

    public function setScore($score) {
        // Mettre à jour en BD
        $pdo = Connection::get();
        $stmt = $pdo->prepare("UPDATE scores SET score = :score WHERE pseudo = :pseudo");
        $stmt->execute([
            ':score' => $score,
            ':pseudo' => $this->name
        ]);

        $this->score = $score;
    }

    public function save() {
        $pdo = Connection::get();
        $stmt = $pdo->prepare("INSERT INTO scores (pseudo, score) VALUES (:pseudo, :score)");
        $stmt->execute([
            ':pseudo' => $this->name,
            ':score' => $this->score
        ]);
    }
}
?>