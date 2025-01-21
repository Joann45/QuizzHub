<?php
namespace Database;

use PDO;
use Exception;

class Connection {
    private static $instance;

    public static function get(): PDO {
        if (!self::$instance) {
            try {
                // ...autres paramètres...
                self::$instance = new PDO('sqlite:Data/quizz.db');
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {
                die('Erreur de connexion : ' . $e->getMessage());
            }
        }
        return self::$instance;
    }

    public static function initDB() {
        try {
            $pdo = new PDO('sqlite:Data/quizz.db');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS scores (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    pseudo TEXT NOT NULL,
                    score INTEGER NOT NULL
                )
            ");
        } catch (Exception $e) {
            echo 'Erreur lors de la création de la base : ' . $e->getMessage();
        }
    }
}
?>