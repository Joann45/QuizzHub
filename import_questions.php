<?php
session_start();
require 'Classes/AutoLoader.php';
AutoLoader::register();

use Ressource\QuestionRepository;

if (!isset($_SESSION['userName'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['importFile']) && $_FILES['importFile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['importFile']['tmp_name'];
        $fileName = $_FILES['importFile']['name'];
        $fileSize = $_FILES['importFile']['size'];
        $fileType = $_FILES['importFile']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = ['json'];
        if (in_array($fileExtension, $allowedfileExtensions)) {
            try {
                QuestionRepository::importFromJSON($fileTmpPath);
                echo 'Importation réussie ! <a href="questions.php">Retourner à la liste des questions</a>';
                exit;
            } catch (\Exception $e) {
                echo 'Erreur lors de l\'importation : ' . htmlspecialchars($e->getMessage());
            }
        } else {
            echo 'Type de fichier non autorisé. Veuillez télécharger un fichier JSON.';
        }
    } else {
        echo 'Erreur lors du téléchargement du fichier.';
    }
}

// Inclure la barre de navigation
require 'Classes/Ressource/navbar.php';

echo '<link rel="stylesheet" href="css/styles.css">';
?>

<form method="post" enctype="multipart/form-data">
    <label>Importer des questions (fichier JSON) :</label><br><br>
    <input type="file" name="importFile" accept=".json" required><br><br>
    <input type="submit" value="Importer">
</form>