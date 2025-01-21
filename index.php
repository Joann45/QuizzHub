<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizzHub</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php
    require 'Classes/AutoLoader.php';
    AutoLoader::register();
    use Ressource\QuestionRepository;

    // Inclure la barre de navigation
    require 'Classes/Ressource/navbar.php';

    $availableQuestions = count(QuestionRepository::findAll());
    echo '<p>Nombre de questions disponibles : ' . $availableQuestions . '</p>';
    session_start();

    if (!file_exists('Data/quizz.db')) {
        \Database\Connection::initDB();
    }

    if (!isset($_SESSION['userName'])) {
        if (isset($_GET['action']) && $_GET['action'] === 'start') {
            $_SESSION['userName'] = $_POST['userName'];
            $_SESSION['quizLimit'] = (int)$_POST['quizLimit'];
            $_SESSION['currentQuestion'] = 0;
            header('Location: index.php');
            exit;
        } else {
            echo '<form method="post" action="index.php?action=start">';
            echo '<label>Entrez votre pseudo : </label>';
            echo '<input type="text" name="userName" required><br><br>';
            echo '<label>Nombre de questions à poser :</label>';
            echo '<input type="number" name="quizLimit" min="1" required><br><br>';
            echo '<input type="submit" value="Commencer">';
            echo '</form>';
            exit;
        }
    }
    if (!isset($_SESSION['currentQuestion'])) {
        $_SESSION['currentQuestion'] = 0;
    }

    if (isset($_GET['action']) && $_GET['action'] === 'analyse') {
        $questionId = $_POST['id'];
        $userAnswers = $_POST['question_'.$questionId] ?? null;
        $_SESSION['userAnswers'][$questionId] = $userAnswers;
        $_SESSION['currentQuestion']++;
        header('Location: index.php');
        exit;
    }

    $question = QuestionRepository::findById($_SESSION['currentQuestion']);
    if ($_SESSION['currentQuestion'] >= $_SESSION['quizLimit'] || !$question) {
        $score = isset($_SESSION['score']) ? $_SESSION['score'] : 0;
        $player = new \Database\Joueur($_SESSION['userName'], $score);
        try {
            $player->save();
        } catch (\Exception $e) {
            // ...gérer l'erreur...
        }
        header('Location: result.php');
        exit;
    } else {
        $question->show();
    }
    ?>
</body>
</html>