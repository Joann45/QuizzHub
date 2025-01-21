<?php
namespace Ressource;

abstract class BaseQuestion {
    protected $id;
    protected $intitule;
    protected $reponses;
    protected $type;

    public function __construct($id, $intitule, array $reponses, $type) {
        $this->id = $id;
        $this->intitule = $intitule;
        $this->reponses = $reponses;
        $this->type = $type;
    }

    public function getId(){ return $this->id; }
    public function getIntitule(){ return $this->intitule; }
    public function getReponses(){ return $this->reponses; }
    public function getType(){ return $this->type; }
    

    // Méthode show à redéfinir
    public abstract function show();
}
?>