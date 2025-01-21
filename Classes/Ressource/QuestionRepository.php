<?php
namespace Ressource;

class QuestionRepository {
    public static function findAll(): array {
        $file_json = file_get_contents('Data/questions.json');
        $rawData = json_decode($file_json, true) ?: [];
        $questions = [];
        foreach ($rawData as $q) {
            $answers = [];
            foreach ($q['reponses'] as $r) {
                $answers[] = new Answer($r['id'], $r['intitule'], $r['correct']);
            }
            switch($q['type']) {
                case 'text':
                    $questions[] = new TextQuestion($q['id'], $q['intitule'], $answers, $q['type']);
                    break;
                case 'radio':
                    $questions[] = new RadioQuestion($q['id'], $q['intitule'], $answers, $q['type']);
                    break;
                case 'checkbox':
                    $questions[] = new CheckboxQuestion($q['id'], $q['intitule'], $answers, $q['type']);
                    break;
                default:
                    // ... Gérer autres cas...
                    break;
            }
        }
        return $questions;
    }

    public static function findById(int $id): ?BaseQuestion {
        foreach (self::findAll() as $question) {
            if ($question->getId() === $id) {
                return $question;
            }
        }
        return null;
    }

    public static function createFromRequest(array $postData): void {
        $file_json = file_get_contents('Data/questions.json');
        $questions = json_decode($file_json, true) ?: [];
        $newId = count($questions);

        $reponses = [];
        if (!empty($postData['answers'])) {
            foreach ($postData['answers'] as $index => $txt) {
                $reponses[] = [
                    'id' => $index,
                    'intitule' => $txt,
                    'correct' => (isset($postData['correct']) 
                                 && in_array($index, $postData['correct'], true))
                ];
            }
        }        $questions[] = [
            'id' => $newId,
            'intitule' => $postData['intitule'],
            'type' => $postData['type'],
            'reponses' => $reponses
        ];

        file_put_contents('Data/questions.json', json_encode($questions, JSON_PRETTY_PRINT));
    }

    public static function updateFromRequest(array $postData): void {
        $file_json = file_get_contents('Data/questions.json');
        $questions = json_decode($file_json, true) ?: [];

        foreach ($questions as &$question) {
            if ($question['id'] === (int)$postData['id']) {
                $question['intitule'] = $postData['intitule'];
                $question['type'] = $postData['type'];

                $reponses = [];
                if (!empty($postData['answers'])) {
                    foreach ($postData['answers'] as $index => $txt) {
                        $reponses[] = [
                            'id' => $index,
                            'intitule' => $txt,
                            'correct' => (isset($postData['correct']) 
                                         && in_array($index, $postData['correct'], true))
                        ];
                    }
                }
                $question['reponses'] = $reponses;
                break;
            }
        }

        file_put_contents('Data/questions.json', json_encode($questions, JSON_PRETTY_PRINT));
    }



    public static function exportToJSON(string $filePath): void {
        $questions = self::findAll();
        $exportData = [];
        foreach ($questions as $question) {
            $reponses = [];
            foreach ($question->getReponses() as $reponse) {
                $reponses[] = [
                    'id' => $reponse->getId(),
                    'intitule' => $reponse->getIntitule(),
                    'correct' => $reponse->isCorrect()
                ];
            }
            $exportData[] = [
                'id' => $question->getId(),
                'intitule' => $question->getIntitule(),
                'type' => $question->getType(),
                'reponses' => $reponses
            ];
        }
        file_put_contents($filePath, json_encode($exportData, JSON_PRETTY_PRINT));
    }

    public static function importFromJSON(string $filePath): void {
        if (!file_exists($filePath)) {
            throw new \Exception("Le fichier $filePath n'existe pas.");
        }

        $file_json = file_get_contents($filePath);
        $importData = json_decode($file_json, true);
        if ($importData === null) {
            throw new \Exception("Le fichier $filePath n'est pas un JSON valide.");
        }

        foreach ($importData as $q) {
            // Vérifier si la question existe déjà
            if (self::findById($q['id'])) {
                continue; // Skip if exists
            }

            $reponses = [];
            foreach ($q['reponses'] as $r) {
                $reponses[] = [
                    'id' => $r['id'],
                    'intitule' => $r['intitule'],
                    'correct' => $r['correct']
                ];
            }

            $questions = self::findAll();
            $questions[] = [
                'id' => $q['id'],
                'intitule' => $q['intitule'],
                'type' => $q['type'],
                'reponses' => $reponses
            ];

            file_put_contents('Data/questions.json', json_encode($questions, JSON_PRETTY_PRINT));
        }
    }
}
?>