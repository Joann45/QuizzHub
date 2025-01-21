<?php
namespace Ressource;

class TextQuestion extends BaseQuestion {
    public function show() {
        echo '<h2>' . htmlspecialchars($this->intitule) . '</h2>';
        echo '<form method="post" action="index.php?action=analyse">';
        echo '<input type="hidden" name="id" value="' . htmlspecialchars($this->id) . '">';
        echo '<input type="hidden" name="type" value="text">';
        echo '<input type="text" name="question_' . htmlspecialchars($this->id) . '">';
        echo '<input type="submit" value="Valider">';
        echo '</form>';
    }
}
?>