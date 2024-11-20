<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['animal_id'])) {
        $animalId = $_POST['animal_id'];

        // Connexion à MongoDB Atlas avec les informations de connexion distantes
        if (extension_loaded("mongodb")) {
            try {
                $manager = new MongoDB\Driver\Manager("mongodb+srv://twobrochcorp:OYe4FL8B4VF7DkAp@cluster0.bvu0w.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0");

                // Préparer la requête pour mettre à jour le compteur de clics
                $bulk = new MongoDB\Driver\BulkWrite;
                $bulk->update(
                    ['pidAnimal' => $animalId], // Filtrer par pidAnimal
                    ['$inc' => ['click_count' => 1]], // Incrémenter click_count
                    ['multi' => false, 'upsert' => true] // Upsert : créer s'il n'existe pas
                );

                // Exécuter la requête
                $result = $manager->executeBulkWrite('votre_base_de_donnees.animaux_clics', $bulk);
                echo "Compteur de clics mis à jour.";
            } catch (MongoDB\Driver\Exception\Exception $e) {
                die("Erreur lors de la mise à jour du compteur : " . $e->getMessage());
            }
        } else {
            die("Extension MongoDB non activée. Vérifiez la configuration de PHP.");
        }
    } else {
        die("ID de l'animal non fourni.");
    }
} else {
    die("Requête non autorisée.");
}
?>
