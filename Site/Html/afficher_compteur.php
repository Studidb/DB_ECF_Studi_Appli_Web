<?php
// Informations de connexion à la base de données MySQL
$host = '127.0.0.1'; // Vous pouvez aussi utiliser 'localhost' à la place de '127.0.0.1'
$dbname = 'u386540360_4rcadiaAdmin';
$username = 'root'; // Utilisateur de la base de données
$password = ''; // Mot de passe associé

try {
    // Connexion à MySQL avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données.";
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
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
        // Connexion à MongoDB Atlas avec les informations de connexion distantes
        $manager = new MongoDB\Driver\Manager("mongodb+srv://twobrochcorp:OYe4FL8B4VF7DkAp@cluster0.bvu0w.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0");

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
