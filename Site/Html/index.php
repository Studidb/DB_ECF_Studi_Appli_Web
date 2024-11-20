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
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

//Connexion à la session
session_start();
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Initialisation de la variable $utilisateur
$utilisateur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoi'])) {
    if (!empty($_POST['email']) && !empty($_POST['motDePasse'])) {
        $email = htmlspecialchars($_POST['email']);
        $motDePasse = htmlspecialchars($_POST['motDePasse']);

        // Préparer la requête pour récupérer l'utilisateur correspondant à l'email donné
        $utilisateur = $pdo->prepare('SELECT * FROM utilisateur WHERE email = ?');
        $utilisateur->execute([$email]);

        // Vérifier si l'utilisateur existe
        if ($utilisateur->rowCount() > 0) {
            // Récupérer les informations de l'utilisateur
            $userInfo = $utilisateur->fetch(PDO::FETCH_ASSOC);

            // Vérifier si le mot de passe fourni correspond au mot de passe haché en base de données
            if (password_verify($motDePasse, $userInfo['motDePasse'])) {
                // Si le mot de passe est correct, démarrer la session utilisateur
                $_SESSION['email'] = $email;
                $_SESSION['roleUtilisateur'] = $userInfo['roleUtilisateur'];
            } else {
                echo "<script>alert('Mot de passe incorrect.');</script>";
            }
        } else {
            echo "<script>alert('Utilisateur non trouvé.');</script>";
        }
    } else {
        echo "<script>alert('Veuillez remplir tous les champs.');</script>";
    }
}
    // Vérification de la connexion dans le reste du code
$isUserConnected = isset($_SESSION['email']);

try {
    // Connexion à MongoDB Atlas sans utiliser Composer avec des options simplifiées
    $uri = "mongodb+srv://twobrochcorp:OYe4FL8B4VF7DkAp@cluster0.bvu0w.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0";
    $uriOptions = [
        'tlsAllowInvalidCertificates' => true, // Ignorer les erreurs de certificat (à n'utiliser que pour tester)
    ];

    $manager = new MongoDB\Driver\Manager($uri, $uriOptions);
    
    // Envoi d'une commande ping pour vérifier la connexion
    $command = new MongoDB\Driver\Command(['ping' => 1]);
    $cursor = $manager->executeCommand('admin', $command);
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Erreur lors de la connexion à MongoDB Atlas : " . $e->getMessage();
}
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
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Veterinaire")) {
                                // L'utilisateur est connecté en tant que vétérinaire, on affiche son espace
                                echo '<li><a href="/Site/Html/Veterinaire.php">Espace Veto</a></li>';
                            }
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Employe")) {
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
