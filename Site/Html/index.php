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
    <link rel="stylesheet" href="/Site/Css/accueil.css?v=2">
    <link rel="stylesheet" href="/Site/Css/styles.css?v=2">
    <link rel="stylesheet" href="/Site/Css/stylesMobile.css?v=2">

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
                        <?php 
                            // Vérifier si l'utilisateur est connecté et s'il a le rôle "Veterinaire"
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Veterinaire"|| $_SESSION['roleUtilisateur'] == "Admin")) {
                                // L'utilisateur est connecté en tant que vétérinaire, on affiche son espace
                                echo '<li><a href="/Site/Html/Veterinaire.php">Espace Veto</a></li>';
                            }
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Employe"|| $_SESSION['roleUtilisateur'] == "Admin")) {
                                // L'utilisateur est connecté en tant qu'employé, on affiche son espace
                                echo '<li><a href="/Site/Html/Employe.php">Espace Employé</a></li>';
                            } 
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && $_SESSION['roleUtilisateur'] == "Admin") {
                                // L'utilisateur est connecté en tant qu'administrateur, on affiche son espace
                                echo '<li><a href="/Site/Html/Administrateur.php">Espace Administrateur</a></li>';
                            } 
                        ?>
                    </ul>
                    <ul>        
                    <?php 
                        // Vérifier si l'utilisateur est connecté en utilisant la session
                        if (isset($_SESSION['email'])) {
                            // L'utilisateur est connecté, on affiche un bouton de déconnexion
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
    </header>

    <!-- Les différentes Sections de la page d'acceuil -->
    <main>  
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
                <?php
                echo '<li class="sansPunaise"><button id="Connexion" onclick="ouvrirPopupAvis()">Laissez un avis</button></li>';
                ?>
                <!-- Popup formulaire Connexion -->
                <div id="popupAvis" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" onclick="fermerPopupAvis()">&times;</span>
                        <h2>Laissez un avis</h2>
                        <form action="" method="POST">
                            <label for="pseudo">Votre pseudonyme :</label>
                            <input type="text" id="pseudo" name="pseudo" required autocomplete="on" value="Anonyme">
                            
                            <label for="message">Votre message :</label>
                            <textarea id="message" name="message" required rows="6" cols="50"></textarea>
                            
                            <button type="submit" id="envoiAvis" name="envoiAvis">Poster votre message</button>
                        </form>
                    </div>
                </div>

                <?php
                // Vérifier si le formulaire d'avis a été soumis
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoiAvis'])) {
                    // Récupérer les données du formulaire sans vérifier si elles sont vides
                    $pseudo = htmlspecialchars($_POST['pseudo']);
                    $message = htmlspecialchars($_POST['message']);

                    // Préparer l'insertion dans la base de données
                    $insertion = $pdo->prepare('INSERT INTO messages (pseudo, message) VALUES (?, ?)');
                    $insertion->execute(array($pseudo, $message));
                }

                // Récupérer les avis validés pour les afficher sur la page d'accueil
                $requeteAvisValides = $pdo->prepare('SELECT * FROM messagesvalides');
                $requeteAvisValides->execute();
                $avisValides = $requeteAvisValides->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <div id="avisVisiteur">
                    <h2>Avis des Visiteurs</h2>
                    <table id="avisTable">
                        <thead>
                            <tr>
                                <th>Pseudonyme</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($avisValides as $avisValide): ?>
                                <tr>
                                    <td><?= htmlspecialchars($avisValide['pseudo']); ?></td>
                                    <td><?= htmlspecialchars($avisValide['message']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
            <!-- Script Js affichage du menu Navigation -->
            <script src="/Script/Js/script.js"></script>
</body>
</html>
