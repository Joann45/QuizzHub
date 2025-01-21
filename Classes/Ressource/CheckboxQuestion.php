<?php
namespace Ressource;

class CheckboxQuestion extends BaseQuestion {
    public function show() {
        echo '<h2>' . htmlspecialchars($this->intitule) . '</h2>';
        echo '<form method="post" action="index.php?action=analyse">';
        echo '<input type="hidden" name="id" value="' . htmlspecialchars($this->id) . '">';
        echo '<input type="hidden" name="type" value="checkbox">';
        foreach ($this->reponses as $reponse) {
            echo '<input type="checkbox" name="question_' . htmlspecialchars($this->id) . '[]" value="' . htmlspecialchars($reponse->getId()) . '">';
            echo '<label for="question_' . htmlspecialchars($this->id) . '">' . htmlspecialchars($reponse->getIntitule()) . '</label><br>';
        }
        echo '<input type="submit" value="Valider">';
        echo '</form>';
    }
}