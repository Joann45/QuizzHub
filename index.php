<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizzHub</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="result.php">Historique</a></li>
            <li><a href="create_question.php">Créer une Question</a></li>
            <li><a href="questions.php">Questions</a></li>
        </ul>
    </nav>

    <?php
    session_start();

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

    require 'Classes/AutoLoader.php';
    AutoLoader::register();

    $availableQuestions = count(Ressource\Question::getQuestions());
    echo '<p>Nombre de questions disponibles : ' . $availableQuestions . '</p>';

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

    $question = Ressource\Question::getQuestionById($_SESSION['currentQuestion']);
    if ($_SESSION['currentQuestion'] >= $_SESSION['quizLimit'] || !$question) {
        try {
            $pdo = new PDO('sqlite:Data/quizz.db');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare('INSERT INTO scores (player, score) VALUES (:player, :score)');
            $score = isset($_SESSION['score']) ? $_SESSION['score'] : 0;
            $stmt->execute([
                ':player' => $_SESSION['userName'],
                ':score' => $score
            ]);
        } catch (\Exception $e) {
            // Gestion des erreurs
        }
        header('Location: result.php');
        exit;
    } else {
        $question->show();
    }
    ?>
</body>
</html>