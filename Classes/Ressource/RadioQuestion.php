<?php
namespace Ressource;

class RadioQuestion extends BaseQuestion {
    public function show() {
        echo '<h2>' . htmlspecialchars($this->intitule) . '</h2>';
        echo '<form method="post" action="index.php?action=analyse">';
        echo '<input type="hidden" name="id" value="' . htmlspecialchars($this->id) . '">';
        echo '<input type="hidden" name="type" value="radio">';
        foreach($this->reponses as $reponse){
            echo '<div class="option">';
            echo '<input type="radio" name="question_' . htmlspecialchars($this->id) 
                 . '" value="' . htmlspecialchars($reponse->getId()) . '">';
            echo '<label>' . htmlspecialchars($reponse->getIntitule()) . '</label><br>';
            echo '</div>';
        }
        echo '<input type="submit" value="Valider">';
        echo '</form>';
    }
}
?>