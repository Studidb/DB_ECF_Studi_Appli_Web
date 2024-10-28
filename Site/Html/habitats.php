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
    <link rel="stylesheet" href="/Site/Css/habitats.css">
    <link rel="stylesheet" href="/Site/Css/styles.css">
    <link rel="stylesheet" href="/Site/Css/stylesMobile.css">
    <title>Habitats Arcadia</title>
</head>
<body>

    <!-- Menu Navigation et Banniere -->
    <header>

        <!-- Section Banniere -->
        <section id="Section_Banniere">
            <div>
                <h1 class="Titre_Banniere">Habitats</h1>
                <img src="/Ressources/Images/Banniere/BanniereArcadia.png" alt="Grande image d'en-tête représentant un zoo">
            </div>
        </section>
        
        <!-- Section Menu de Navigation -->
        <section class="Section_Navigation" id="Section_Navigation">
            <div>
                <nav>
                    <ul id="Menu">
                        <li><a href="/Site/Html/index.php">Accueil</a></li>
                        <li><a href="/Site/Html/services.php">Services</a></li>
                        <li class="page_navigante">Habitats</li>
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

    <!-- Les différentes Sections de la page services -->
    <main>

        <!-- Section Marais -->
        <section id="Section_Marais">
            <h1>Le Marais</h1>
            <hr>
            <div>
                <img src="/Ressources/Images/Habitats/MaraisArcadia.png" alt="Image du Marais d'Arcadia" class="habitat_image alternativeA_border_color">
                <div class="animal" style="display: none;">
                    <h1>Rosie la Flamant Rose</h1>
                    <hr>
                    <img src="/Ressources/Images/Animaux/FlamingoGeneral.png" alt="Flamant Rose" class="animal_image">
                    <h1>Nector le Crocodile</h1>
                    <hr>
                    <img src="/Ressources/Images/Animaux/CrocodileGeneral.png" alt="Crocodile" class="animal_image">
                    <p>L'habitat des marais recrée un environnement humide avec un grand étang, des rives boueuses et une végétation luxuriante. Idéal pour les crocodiles et oiseaux aquatiques, il offre un espace serein et naturel propice à l'observation de la faune des zones marécageuses.</p>
                </div>
            </div>
        </section>
        
        <!-- Section Savane -->
        <section id="Section_Savane">
            <h1>La Savane</h1>
            <hr>
            <div>
                <img src="/Ressources/Images/Habitats/SavaneArcadia.png" alt="Image de la Savane d'Arcadia" class="habitat_image alternativeB_border_color">
                <div class="animal" style="display: none;">
                    <h1>Roger le Lion</h1>
                    <hr>
                    <img src="/Ressources/Images/Animaux/LionGeneral.png" alt="Lion" class="animal_image">
                    <h1>Giselle la Girafe</h1>
                    <hr>
                    <img src="/Ressources/Images/Animaux/GirafeGeneral.png" alt="Girafe" class="animal_image">
                    <p>L'habitat de la savane recrée une vaste plaine ouverte, parsemée de hautes herbes dorées et de rares acacias. Il offre un espace idéal pour les girafes, zèbres, antilopes et autres herbivores, ainsi que pour les prédateurs emblématiques comme les lions. Ce cadre naturel permet d'observer la faune dans un environnement simulant parfaitement la chaleur et l'étendue infinie de la savane africaine.</p>
                </div>
            </div>
        </section>

        <!-- Section Jungle -->
        <section id="Section_Jungle">
            <h1>La Jungle</h1>
            <hr>
            <div>
                <img src="/Ressources/Images/Habitats/JungleArcadia.png" alt="Image de la Jungle d'Arcadia" class="habitat_image alternativeA_border_color">
                <div class="animal" style="display: none;">
                    <h1>Ficelle le Serpent</h1>
                    <hr>   
                    <img src="/Ressources/Images/Animaux/SerpentGeneral.png" alt="Serpent" class="animal_image">
                    <h1>Jack le Jaguar</h1>
                    <hr>   
                    <img src="/Ressources/Images/Animaux/JaguarGeneral.png" alt="Jaguar" class="animal_image">
                    <p>L'habitat de la jungle recrée une forêt tropicale dense, avec une végétation épaisse, des lianes enchevêtrées et des rivières sinueuses. Cet environnement luxuriant est parfait pour les singes, oiseaux exotiques et autres animaux tropicaux. La canopée haute et la richesse de la flore offrent un cadre propice à l'observation de la vie sauvage dans l'un des écosystèmes les plus diversifiés et mystérieux de la planète.</p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
