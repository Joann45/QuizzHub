<?php
namespace Ressource;

class Answer {
    private $id;
    private $intitule;
    private $correct;

    public function __construct(int $id, string $intitule, bool $correct) {
        $this->id = $id;
        $this->intitule = $intitule;
        $this->correct = $correct;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getIntitule(): string {
        return $this->intitule;
    }

    public function isCorrect(): bool {
        return $this->correct;
    }
}
?>