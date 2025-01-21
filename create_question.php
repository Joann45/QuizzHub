<?php
session_start();
require 'Classes/AutoLoader.php';
AutoLoader::register();

use Ressource\Question;

$editing = false;
if (isset($_GET['id'])) {
    $editing = true;
    $id = $_GET['id'];
    $question = \Ressource\QuestionRepository::findById($id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($editing) {
        \Ressource\QuestionRepository::updateFromRequest($id, $_POST);
        echo 'Question mise à jour avec succès ! <a href="questions.php">Revenir</a>';
    } else {
        \Ressource\QuestionRepository::createFromRequest($_POST);
        echo 'Question créée avec succès ! <a href="create_question.php">Revenir</a>';
    }
    exit;
}

// Inclure la barre de navigation
require 'Classes/Ressource/navbar.php';

echo '<link rel="stylesheet" href="css/styles.css">';
?>

<form method="post">
    <label>Intitulé :</label><br>
    <input type="text" name="intitule" value="<?= isset($question) ? htmlspecialchars($question->getIntitule()) : '' ?>" required><br><br>

    <label>Type :</label><br>
    <select name="type">
        <option value="text" <?= isset($question) && $question->getType() === 'text' ? 'selected' : '' ?>>Texte</option>
        <option value="radio" <?= isset($question) && $question->getType() === 'radio' ? 'selected' : '' ?>>Radio</option>
        <option value="checkbox" <?= isset($question) && $question->getType() === 'checkbox' ? 'selected' : '' ?>>Checkbox</option>
    </select><br><br>

    <label>Réponses :</label>
    <div id="answers">
        <?php for ($i = 0; $i < 4; $i++): ?>
            <div>
                <input type="text" name="answers[<?= $i ?>]" placeholder="Réponse <?= $i + 1 ?>" value="<?= isset($question) ? htmlspecialchars($question->getReponses()[$i]->getIntitule() ?? '') : '' ?>" <?= $i === 0 ? 'required' : '' ?>>
                <input type="checkbox" name="correct[]" value="<?= $i ?>" <?= isset($question) && in_array($i, array_map(function($r) { return $r->getId(); }, $question->getReponses()), true) ? 'checked' : '' ?>> Correct
            </div>
        <?php endfor; ?>
    </div><br>

    <input type="submit" value="<?= $editing ? 'Mettre à jour la question' : 'Créer la question' ?>">
</form>