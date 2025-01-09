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
        echo '<h2>' . $this->intitule . '</h2>';
        echo '<form method="post" action="analyse.php">';
        switch($this->type){
            case 'text':
                echo '<input type="text" name="question_' . $this->id . '">';
                break;
            case 'radio':
                foreach($this->reponses as $key => $reponse){
                    echo '<input type="radio" name="question_' . $this->id . '" value="' . $key . '">' . $reponse['intitule'] . '<br>';
                }
                break;
            case 'checkbox':
                foreach($this->reponses as $key => $reponse){
                    echo '<input type="checkbox" name="question_' . $this->id . '[]" value="' . $key . '">' . $reponse['intitule'] . '<br>';
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