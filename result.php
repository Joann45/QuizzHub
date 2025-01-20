<?php
session_start();
require 'Classes/AutoLoader.php';
AutoLoader::register();

use Ressource\Result;

if (!isset($_SESSION['userName'])) {
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['userAnswers']) || empty($_SESSION['userAnswers'])) {
    echo 'Aucune réponse enregistrée.';
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

Result::showHistory($_SESSION['userAnswers']);

session_destroy();
?>