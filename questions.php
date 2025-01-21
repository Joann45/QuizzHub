
<?php
session_start();
require 'Classes/AutoLoader.php';
AutoLoader::register();

use Ressource\QuestionRepository;

if (!isset($_SESSION['userName'])) {
    header('Location: index.php');
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

$questions = QuestionRepository::findAll();

echo '<h1>Liste des questions disponibles</h1>';
echo '<ul>';
foreach ($questions as $question) {
    echo '<li> <a href="create_question.php?id=' . $question->getId() . '">'. htmlspecialchars($question->getIntitule()) .  '</a></li>';
}
echo '</ul>';
?>