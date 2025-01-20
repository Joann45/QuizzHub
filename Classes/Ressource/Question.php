<?php
namespace Ressource;

class Question{
    private $id;
    private $intitule;
    private $reponses;
    private $type;

    public function __construct($id, $intitule, $reponses, $type){
        $this->id = $id;
        $this->intitule = $intitule;
        $this->reponses = $reponses;
        $this->type = $type;
    }

    public function getId(){
        return $this->id;
    }

    public function getIntitule(){
        return $this->intitule;
    }

    public function getReponses(){
        return $this->reponses;
    }

    public function getType(){
        return $this->type;
    }

    public function show(){
        echo '<h2>' . htmlspecialchars($this->intitule) . '</h2>';
        echo '<form method="post" action="index.php?action=analyse">';
        echo '<input type="hidden" name="id" value="' . htmlspecialchars($this->id) . '">';
        switch($this->type){
            case 'text':
                echo '<input type="hidden" name="type" value="text">';
                echo '<input type="text" name="question_' . htmlspecialchars($this->id) . '">';
                break;
            case 'radio':
                echo '<input type="hidden" name="type" value="radio">';
                foreach($this->reponses as $key => $reponse){
                    echo '<div class="option">';
                    echo '<input type="radio" id="q' . htmlspecialchars($this->id) . '_r' . htmlspecialchars($reponse['id']) . '" name="question_' . htmlspecialchars($this->id) . '" value="' . htmlspecialchars($reponse['id']) . '">';
                    echo '<label for="q' . htmlspecialchars($this->id) . '_r' . htmlspecialchars($reponse['id']) . '">' . htmlspecialchars($reponse['intitule']) . '</label><br>';
                    echo '</div>';
                }
                break;
            case 'checkbox':
                echo '<input type="hidden" name="type" value="checkbox">';
                foreach($this->reponses as $key => $reponse){
                    echo '<div class="option">';
                    echo '<input type="checkbox" id="q' . htmlspecialchars($this->id) . '_r' . htmlspecialchars($reponse['id']) . '" name="question_' . htmlspecialchars($this->id) . '[]" value="' . htmlspecialchars($reponse['id']) . '">';
                    echo '<label for="q' . htmlspecialchars($this->id) . '_r' . htmlspecialchars($reponse['id']) . '">' . htmlspecialchars($reponse['intitule']) . '</label><br>';
                    echo '</div>';
                }
                break;
        }
        echo '<input type="submit" value="Valider">';
        echo '</form>';
    }

    public static function getQuestions(){
        $file_json = file_get_contents('Data/questions.json');
        $questions = json_decode($file_json, true);
        return $questions;
    }

    public static function getQuestionById($id){
        $questions = self::getQuestions();
        foreach($questions as $question){
            if($question['id'] == $id){
                return new Question($question['id'], $question['intitule'], $question['reponses'], $question['type']);
            }
        }
    }
}
?>