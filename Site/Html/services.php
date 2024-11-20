<?php
// Connexion à la base de données MySQL
$host = 'localhost';
$dbname = 'base_test_connectivite';
$username = 'root';
$password = '';

//Connexion à la session
session_start();
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Initialisation de la variable $utilisateur
$utilisateur = null;

if(isset($_POST['envoi'])){
    if(!empty($_POST['email']) AND !empty($_POST['motDePasse'])){
        $email = htmlspecialchars($_POST['email']);
        $motDePasse = htmlspecialchars($_POST['motDePasse']);

        $utilisateur = $pdo->prepare('SELECT * FROM utilisateur WHERE email = ? AND motDePasse = ?');
        $utilisateur->execute(array($email, $motDePasse));

        if($utilisateur->rowCount() > 0){
            $userInfo = $utilisateur->fetch();
            $_SESSION['email'] = $email;
            $_SESSION['motDePasse'] = $motDePasse;
            $_SESSION['roleUtilisateur'] = $userInfo['roleUtilisateur'];
        }
    }

    }
    // Vérification de la connexion dans le reste du code
$isUserConnected = isset($_SESSION['email']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet'>
    <link rel="stylesheet" href="/Site/Css/services.css?v=2">
    <link rel="stylesheet" href="/Site/Css/styles.css?v=2">
    <link rel="stylesheet" href="/Site/Css/stylesMobile.css?v=2">
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
                        <?php 
                            // Vérifier si l'utilisateur est connecté et s'il a le rôle "Veterinaire"
                            if (isset($_SESSION['email']) && isset($_SESSION['motDePasse']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Veterinaire"|| $_SESSION['roleUtilisateur'] == "Admin")) {
                                // L'utilisateur est connecté en tant que vétérinaire, on affiche son espace
                                echo '<li><a href="/Site/Html/Veterinaire.php">Espace Veto</a></li>';
                            }
                            if (isset($_SESSION['email']) && isset($_SESSION['motDePasse']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Employe"|| $_SESSION['roleUtilisateur'] == "Admin")) {
                                // L'utilisateur est connecté en tant que vétérinaire, on affiche son espace
                                echo '<li><a href="/Site/Html/Employe.php">Espace Employé</a></li>';
                            } 
                            if (isset($_SESSION['email']) && isset($_SESSION['motDePasse']) && isset($_SESSION['roleUtilisateur']) && $_SESSION['roleUtilisateur'] == "Admin") {
                                // L'utilisateur est connecté en tant que vétérinaire, on affiche son espace
                                echo '<li><a href="/Site/Html/Administrateur.php">Espace Administrateur</a></li>';
                            } 
                        ?>
                    </ul>
                    <ul>        
                    <?php 
                        // Vérifier si l'utilisateur est connecté en utilisant la session
                        if (isset($_SESSION['email']) && isset($_SESSION['motDePasse'])) {
                            // L'utilisateur est connecté, on affiche l'email
                            echo "<li>" . $_SESSION['email'] . "</li>";
                            echo '<button id="Connexion" onclick="deconnexion()">Déconnexion</button>';
                        } else {
                            // L'utilisateur n'est pas connecté, on affiche le bouton de connexion
                            echo '<li><button id="Connexion" onclick="ouvrirPopup()">Connexion</button></li>';
                        }
                    ?>
                    </ul>
                </nav>
            </div>
            <!-- Popup formulaire connexion -->
            <div id="popupFormulaire" class="modal">
                <div class="modal-content">
                    <span class="close-btn" onclick="fermerPopup()">&times;</span>
                    <h2>Connexion</h2>
                    <form action="" method="POST">
                        <label for="email">Login :</label>
                        <input type="email" id="email" name="email" required autocomplete="off">
                        
                        <label for="motDePasse">Mot de passe :</label>
                        <input type="password" id="motDePasse" name="motDePasse" required>
                        
                        <button type="submit" name="envoi">Se connecter</button>
                    </form>
                </div>
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