<?php
namespace Ressource;

class Result {
    public static function showHistory($userAnswers) {
        session_start();
        $score = 0;
        echo '<h1>Historique des réponses de ' . htmlspecialchars($_SESSION['userName']) . '</h1>';
        foreach ($userAnswers as $questionId => $answer) {
            $question = Question::getQuestionById($questionId);
            if (!$question) continue;
            echo '<h3>' . htmlspecialchars($question->getIntitule()) . '</h3>';
            
            echo '<p><strong>Votre réponse :</strong> ';
            if (is_array($answer)) {
                $userResponses = [];
                foreach ($answer as $ansId) {
                    $userResponses[] = htmlspecialchars($question->getReponses()[$ansId]['intitule']);
                }
                echo implode(', ', $userResponses);
            } else {
                echo htmlspecialchars($question->getReponses()[$answer]['intitule']);
            }
            echo '</p>';

            $correctLabels = [];
            foreach($question->getReponses() as $r) {
                if ($r['correct']) {
                    $correctLabels[] = htmlspecialchars($r['intitule']);
                }
            }

            echo '<p><strong>Bonne(s) réponse(s) :</strong> ' . implode(', ', $correctLabels) . '</p>';

            $isCorrect = false;
            if ($question->getType() === 'checkbox') {
                $correctIds = [];
                foreach($question->getReponses() as $idx => $rep) {
                    if ($rep['correct']) {
                        $correctIds[] = $idx;
                    }
                }
                sort($correctIds);
                $userIds = is_array($answer) ? $answer : [];
                sort($userIds);
                if ($correctIds === $userIds) {
                    $isCorrect = true;
                }
            } elseif ($question->getType() === 'radio') {
                foreach($question->getReponses() as $rep) {
                    if ($rep['correct'] && $rep['id'] == $answer) {
                        $isCorrect = true;
                        break;
                    }
                }
            } elseif ($question->getType() === 'text') {
                $correctText = strtolower($question->getReponses()[0]['intitule']);
                if (strtolower($answer) === $correctText) {
                    $isCorrect = true;
                }
            }

            if ($isCorrect) {
                echo '<p class="correct">Correct !</p>';
                $score++;
            } else {
                echo '<p class="incorrect">Incorrect.</p>';
            }

            echo '<hr>';
        }
        echo '<h2>Score final : ' . $score . ' / ' . count($userAnswers) . '</h2>';
    }
}
?>