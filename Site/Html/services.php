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
    <link rel="stylesheet" href="/Site/Css/services.css">
    <link rel="stylesheet" href="/Site/Css/styles.css">
    <link rel="stylesheet" href="/Site/Css/stylesMobile.css">
    <title>Services Arcadia</title>
</head>
<body>

    <!-- Menu Navigation et Banniere -->
    <header>

        <!-- Section Banniere -->
        <section id="Section_Banniere">
            <div>
                <h1 class="Titre_Banniere">Services</h1>
                <img src="/Ressources/Images/Banniere/BanniereArcadia.png" alt="Grande image d'en-tête représentant un zoo">
            </div>
        </section>
        
        <!-- Section Menu de Navigation -->
        <section class="Section_Navigation" id="Section_Navigation">
            <div>
                <nav>
                    <ul id="Menu">
                        <li><a href="/Site/Html/index.php">Accueil</a></li>
                        <li class="page_navigante">Services</li>
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

    <!-- Les différentes Sections de la page services -->
    <main>

        <!-- Section Train -->
        <section id="Section_Train">
            <h1>Le Petit Train</h1>
            <hr>
            <div>
                    <img src="/Ressources/Images/Services/PetitTrainArcadia.png" alt="Image du petit Train d'Arcadia" class="alternativeA_border_color">
                    <p>Au cœur du zoo d'Arcadia, installé dans la magnifique région de Brocéliande en Bretagne depuis 1960, le petit train du parc offre une manière unique et relaxante de découvrir les différents habitats et animaux. Arcadia est plus qu'un simple zoo : c'est un lieu de découverte et de sensibilisation à la préservation de la faune et de la flore, où chaque visiteur est invité à explorer la diversité du règne animal tout en profitant de services conçus pour leur offrir un moment inoubliable.</p>
                    <p>Le petit train est l’un des services les plus appréciés du parc. Conçu pour permettre aux visiteurs de parcourir le zoo de manière confortable et ludique, il traverse les différents environnements recréés pour nos pensionnaires : de la savane peuplée de majestueuses girafes et de zèbres curieux, aux marais où crocodiles et oiseaux exotiques cohabitent, en passant par la jungle luxuriante abritant singes et reptiles rares.</p>
                    <p>En chemin, les passagers profitent de commentaires audio qui enrichissent leur expérience par des informations fascinantes sur les animaux, leur habitat et les actions menées par le zoo pour la conservation. Grâce à ce service, petits et grands peuvent observer les animaux dans leur environnement tout en apprenant sur leurs comportements et les défis de la préservation de la biodiversité.</p>
                    <p>Le petit train est accessible à tous et s’arrête à plusieurs points d’intérêt stratégiques du parc, permettant aux visiteurs de descendre et de profiter des différentes zones à leur rythme avant de reprendre le circuit. C’est l’option idéale pour les familles avec enfants, les personnes âgées ou celles souhaitant une vue d’ensemble du zoo sans effort.</p>
                    <p>Avec son design écologique et sa motorisation respectueuse de l’environnement, le petit train d’Arcadia s’inscrit dans la volonté du parc de promouvoir un tourisme durable et responsable. Plus qu’un simple moyen de transport, il devient une véritable balade éducative au cœur de la nature, permettant à chacun de vivre une expérience immersive et relaxante tout en se connectant à la beauté du monde animal.</p>
            </div>
        </section>
        
        <!-- Section Restaurant -->
        <section id="Section_Restaurant">
            <h1>La Restauration</h1>
            <hr>
            <div>
                    <img src="/Ressources/Images/Services/RestaurantArcadia.png" alt="Image du Restaurant d'Arcadia" class="alternativeB_border_color">
                    <p>Au cœur de la magnifique forêt de Brocéliande, le zoo d'Arcadia propose plus qu'une simple visite : une expérience immersive et respectueuse de la nature. Fondé en 1960, ce parc zoologique breton abrite une grande variété d’animaux, répartis selon leurs habitats naturels, tels que la savane, la jungle et les marais. Les visiteurs y découvrent des espèces fascinantes tout en bénéficiant de services pensés pour leur confort.</p>
                    <p>Parmi ces services, le restaurant d'Arcadia se distingue par son engagement envers l’écologie et la durabilité. Situé à proximité des enclos, notre espace de restauration offre une vue imprenable sur certains habitats, permettant aux familles de déguster leurs repas tout en admirant les animaux. Dans un cadre verdoyant et chaleureux, nos visiteurs peuvent savourer une cuisine élaborée à partir de produits locaux et biologiques, en harmonie avec les valeurs du parc.</p>
                    <p>Le restaurant est pensé pour tous les âges et propose une carte variée comprenant des options végétariennes et sans allergènes, pour que chacun puisse profiter d’un moment convivial. En optant pour des repas équilibrés, le zoo participe également à la sensibilisation des visiteurs sur l’importance de la préservation de l’environnement. Nos installations sont conçues dans le respect de la nature : les bâtiments utilisent des matériaux recyclés et l'énergie nécessaire au fonctionnement du restaurant est entièrement fournie par des sources renouvelables.</p>
                    <p>Que ce soit pour une pause gourmande ou un déjeuner en famille, le restaurant d'Arcadia Zoo est l'endroit idéal pour se détendre et se ressourcer tout en restant connecté à la nature environnante.</p>
            </div>
        </section>

                <!-- Section Guide -->
                <section id="Section_Guide">
                    <h1>Le Guide</h1>
                    <hr>
                    <div>
                            <img src="/Ressources/Images/Services/GuideArcadia.png" alt="Image du Guide d'Arcadia" class="alternativeA_border_color">
                            <p>Depuis 1960, le zoo d’Arcadia, niché dans la forêt légendaire de Brocéliande en Bretagne, est bien plus qu’un simple lieu d’exposition animale : c’est une aventure éducative et enrichissante. Réparti en différents habitats (savane, jungle, marais), le parc accueille une grande diversité d’espèces tout en mettant un point d'honneur à leur bien-être et à la préservation de l'environnement.</p>
                            <p>Pour accompagner les visiteurs dans leur découverte, le zoo propose un service de guide expert, idéal pour plonger au cœur de l’univers fascinant de ses pensionnaires. Menée par une équipe de passionnés, chaque visite guidée permet d’explorer les secrets de la faune, les particularités des différents écosystèmes et l’engagement du zoo en faveur de la conservation. Nos guides, véritables ambassadeurs de la biodiversité, partagent anecdotes et connaissances, et sont toujours disponibles pour répondre aux questions des visiteurs.</p>
                            <p>Le service de guide s’adresse à tous, des familles aux groupes scolaires, en passant par les amateurs de nature désireux d’approfondir leurs connaissances. Les circuits, soigneusement conçus pour s’adapter aux attentes et au rythme de chaque groupe, permettent de visiter les espaces de manière fluide et organisée. En passant d’un habitat à l’autre, les visiteurs auront l’occasion de mieux comprendre les habitudes de vie des animaux, leur alimentation, ainsi que les efforts menés par le parc pour leur offrir un environnement proche de leur milieu naturel.</p>
                            <p>Que ce soit lors d’une visite en petit groupe ou d’un parcours thématique plus spécifique, le service de guide d’Arcadia est une porte ouverte vers une expérience immersive et éducative. Les visiteurs repartiront avec non seulement de merveilleux souvenirs, mais aussi une compréhension approfondie de la relation entre l’Homme et la nature, ainsi qu’un regard nouveau sur la richesse et la fragilité de la biodiversité.</p>
                    </div>
                </section>
    </main>
</body>
</html>