<?php
session_start();
require 'Classes/AutoLoader.php';
AutoLoader::register();

use Ressource\Question;

if (isset($_POST['intitule'])) {
    $questions = json_decode(file_get_contents('Data/questions.json'), true);
    $newId = count($questions);
    $intitule = $_POST['intitule'];
    $type = $_POST['type'];
    $reponses = [];

    foreach ($_POST['answers'] as $index => $txt) {
        $correct = isset($_POST['correct']) && in_array($index, $_POST['correct']);
        $reponses[] = [
            'id' => $index,
            'intitule' => $txt,
            'correct' => $correct
        ];
    }

    $questions[] = [
        'id' => $newId,
        'intitule' => $intitule,
        'type' => $type,
        'reponses' => $reponses
    ];

    file_put_contents('Data/questions.json', json_encode($questions, JSON_PRETTY_PRINT));
    echo 'Question créée avec succès ! <a href="create_question.php">Revenir</a>';
    exit;
}

echo '<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="result.php">Historique</a></li>
        <li><a href="create_question.php">Créer une Question</a></li>
        <li><a href="questions.php">Questions</a></li>
    </ul>
</nav>';

echo '<link rel="stylesheet" href="css/styles.css">';
?>

<form method="post">
    <label>Intitulé :</label><br>
    <input type="text" name="intitule" required><br><br>

    <label>Type :</label><br>
    <select name="type">
        <option value="text">Texte</option>
        <option value="radio">Radio</option>
        <option value="checkbox">Checkbox</option>
    </select><br><br>

    <label>Réponses :</label>
    <div id="answers">
        <div>
            <input type="text" name="answers[0]" placeholder="Réponse 1" required>
            <input type="checkbox" name="correct[]" value="0"> Correct
        </div>
        <div>
            <input type="text" name="answers[1]" placeholder="Réponse 2">
            <input type="checkbox" name="correct[]" value="1"> Correct
        </div>
        <div>
            <input type="text" name="answers[2]" placeholder="Réponse 3">
            <input type="checkbox" name="correct[]" value="2"> Correct
        </div>
        <div>
            <input type="text" name="answers[3]" placeholder="Réponse 4">
            <input type="checkbox" name="correct[]" value="3"> Correct
        </div>
    </div><br>

    <input type="submit" value="Créer la question">
</form>