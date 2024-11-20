<?php
// Connexion à la base de données MySQL
$host = 'localhost';
$dbname = 'base_test_connectivite';
$username = 'root';
$password = '';

try {
    // Connexion à MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Charger la table des animaux
    $stmt = $pdo->prepare('SELECT * FROM animaux');
    $stmt->execute();
    $animaux_table = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur lors de la connexion à MySQL : " . $e->getMessage());
}

// Connexion à MongoDB pour obtenir les compteurs de clics
$clics = [];
if (extension_loaded("mongodb")) {
    try {
        $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

        // Créer une requête pour récupérer tous les compteurs de clics des animaux
        $query = new MongoDB\Driver\Query([]);
        $cursor = $manager->executeQuery('votre_base_de_donnees.animaux_clics', $query);

        // Transformer les données de MongoDB en tableau associatif
        foreach ($cursor as $animalMongo) {
            $clics[$animalMongo->pidAnimal] = $animalMongo->click_count;
        }

    } catch (MongoDB\Driver\Exception\Exception $e) {
        die("Erreur lors de la connexion à MongoDB : " . $e->getMessage());
    }
} else {
    die("Extension MongoDB non activée. Vérifiez la configuration de PHP.");
}

// Affichage des informations (nom de l'animal, nombre de clics et image)
echo "<h1>Compteurs de clics des animaux</h1>";
echo "<table border='1'>";
echo "<tr><th>Nom de l'animal</th><th>Image de l'animal</th><th>Nombre de clics</th></tr>";

if (!empty($animaux_table)) {
    foreach ($animaux_table as $animal) {
        if (isset($animal['pidAnimal']) && isset($animal['nom']) && isset($animal['image'])) {
            $pidAnimal = $animal['pidAnimal'];
            $nomAnimal = htmlspecialchars($animal['nom']);

            // Conversion de l'image en base64
            $imageData = $animal['image'];
            if (!empty($imageData)) {
                $imageBase64 = base64_encode($imageData);
                $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;
            } else {
                // Placeholder pour l'image si aucune n'est disponible
                $imageSrc = 'https://via.placeholder.com/100';
            }

            $nombreClics = isset($clics[$pidAnimal]) ? $clics[$pidAnimal] : 0;

            echo "<tr>";
            echo "<td>" . $nomAnimal . "</td>";
            echo "<td><img src='" . $imageSrc . "' alt='Image de l'animal " . $nomAnimal . "' style='width: 100px; height: 100px;'></td>";
            echo "<td>" . $nombreClics . "</td>";
            echo "</tr>";
        }
    }
} else {
    echo "<tr><td colspan='3'>Aucun animal trouvé dans la base de données.</td></tr>";
}

echo "</table>";
?>
