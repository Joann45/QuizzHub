<?php
namespace Database;

class PlayerRepository {
    public static function save(Joueur $joueur) {
        $joueur->save();
    }
}