<?php
// Connexion à la base de données MySQL
$host = 'localhost';
$dbname = 'base_test_connectivite';
$username = 'root';
$password = '';

try {
    // Créer une connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Configurer PDO pour afficher les erreurs en tant qu'exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour récupérer le message avec l'ID 1
    $stmt = $pdo->prepare('SELECT message FROM messages WHERE id = :id');
    $stmt->execute(['id' => 1]); 
    $message = $stmt->fetchColumn(); 

    // Message par défaut si rien n'est trouvé
    if (!$message) {
        $message = "Aucun message trouvé."; 
    }

    // En cas d'erreur, afficher le message d'erreur
} catch (PDOException $e) {
    echo "Erreur de connexion ou de requête : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet'>
    <link rel="stylesheet" href="/Site/Css/accueil.css">
    <link rel="stylesheet" href="/Site/Css/styles.css">
    <link rel="stylesheet" href="/Site/Css/stylesMobile.css">
    <title>Accueil Arcadia</title>
</head>
<body>

    <!-- Menu Navigation et Banniere -->
    <header>

        <!-- Section Banniere -->
        <section id="Section_Banniere">
            <div>
                <h1 class="Titre_Banniere">Accueil</h1>
                <img src="/Ressources/Images/Banniere/BanniereArcadia.png" alt="Grande image d'en-tête représentant un zoo">
            </div>
        </section>
        
        <!-- Section Menu de Navigation -->
        <section class="Section_Navigation" id="Section_Navigation">
            <div>
                <nav>
                    <ul id="Menu">
                        <li class="page_navigante">Accueil</li>
                        <li><a href="/Site/Html/services.php">Services</a></li>
                        <li><a href="/Site/Html/habitats.php">Habitats</a></li>
                        <li><a href="/Site/Html/contact.php">Contact</a></li>
                    </ul>
                    <ul>
                        <li id="Connexion">Connexion</li>
                    </ul>
                </nav>
            </div>
        </section>

        <!-- Menu Burger pour les petits écrans -->
        <section class="burger-menu" id="burger-menu">
            <div class="barre"></div>
            <div class="barre"></div>
            <div class="barre"></div>
        </section>

        <!-- Script Js affichage du menu Navigation -->
        <script src="/Script/Js/script.js"></script>
    </header>

    <!-- Les différentes Sections de la page d'acceuil -->
    <main>

        <!-- Test affichage message BDD -->
        <p><?php echo htmlspecialchars($message); ?></p>

        <p><?php
// Vérifier que l'extension MongoDB est activée
if (extension_loaded("mongodb")) {
    echo "<p>Extension MongoDB est activée.</p>";

    // Connexion à MongoDB
    try {
        $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

        // Créer un document pour insertion
        $bulk = new MongoDB\Driver\BulkWrite;
        $document = ['_id' => new MongoDB\BSON\ObjectID, 'name' => 'Test User', 'email' => 'test@example.com'];
        $bulk->insert($document);

        // Exécuter l'insertion
        $manager->executeBulkWrite('test_database.test_collection', $bulk);

        echo "<p>Document inséré avec succès dans MongoDB.</p>";
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "<p>Erreur lors de la connexion ou de l'insertion dans MongoDB : " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Extension MongoDB n'est pas activée. Vérifiez la configuration de PHP.</p>";
}
?></p>

        <!-- Section à propos -->
        <section id="Section_APropos">
            <h1>Qu'est ce qu'Arcadia ?</h1>
            <hr>
            <div>
                <img src="/Ressources/Images/Animaux/GirafeGeneral.png" alt="Image d'une girafe dans son habitat">
                <img src="/Ressources/Images/Animaux/LionGeneral.png" alt="Image d'un lion dans son habitat">
            </div>
            <p>
                Situé en Bretagne, près de la forêt de Brocéliande, le zoo d’Arcadia accueille depuis 1960 une grande diversité d’animaux répartis par habitat (savane, jungle, marais). Engagé dans la préservation de la faune et la durabilité, il offre aux visiteurs une expérience immersive et éducative, en mettant l'accent sur le bien-être animal et le respect de l’environnement.
            </p>
        </section>
        
        <!-- Section Services -->
        <section id="Section_Services">
            <h1>Nos Services</h1>
            <hr>
            <div class="accueil_carte_conteneur">
                <div>
                    <img src="/Ressources/Images/Services/RestaurantArcadia.png" alt="Image du Restaurant d'Arcadia" class="alternativeA_border_color">
                    <p>Le restaurant d’Arcadia propose des repas bio avec vue sur les habitats, tout en respectant l’engagement écologique du zoo.</p>
                </div>
                <div>
                    <img src="/Ressources/Images/Services/PetitTrainArcadia.png" alt="Image du train d'Arcadia" class="alternativeB_border_color">
                    <p>Le petit train permet de découvrir les habitats du zoo confortablement, avec des explications sur la faune.</p>
                </div>
                <div>
                    <img src="/Ressources/Images/Services/GuideArcadia.png" alt="Image d'un guide d'Arcadia" class="alternativeA_border_color">
                    <p>Les guides partagent leurs connaissances pour une visite immersive et éducative des animaux et écosystèmes du parc.</p>
                </div>
            </div>
        </section>
        
        <!-- Section Habitats -->
        <section id="Section_Habitats">
            <h1>Les Habitats</h1>
            <hr>
            <div class="accueil_carte_conteneur">
                <div class="acceuil_carte">
                    <img src="/Ressources/Images/Habitats/MaraisArcadia.png" alt="Image du Restaurant d'Arcadia" class="alternativeB_border_color">
                    <p>Environnement humide avec étang et végétation dense, idéal pour les crocodiles et oiseaux aquatiques.</p>
                </div>
                <div class="acceuil_carte">
                    <img src="/Ressources/Images/Habitats/SavaneArcadia.png" alt="Image du train d'Arcadia" class="alternativeA_border_color">
                    <p>Grande plaine ouverte avec hautes herbes et acacias, abritant girafes, zèbres et antilopes.</p>
                </div>
                <div class="acceuil_carte">
                    <img src="/Ressources/Images/Habitats/JungleArcadia.png" alt="Image d'un guide d'Arcadia" class="alternativeB_border_color">
                    <p>Forêt tropicale dense avec lianes et rivières, peuplée de singes et d’oiseaux exotiques.</p>
                </div>
            </div>
        </section>
        
        <!-- Section Avis -->
        <section id="Section_Avis">
            <div>
                <h1>Avis</h1>
                <hr>
            </div>
        </section>
    </main>
</body>
</html>