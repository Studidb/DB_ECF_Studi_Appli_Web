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

$stmt = $pdo->prepare('SELECT * FROM `service`');
$stmt->execute();
$service_table = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <!-- Section d'affichage dynamique des services -->
        <section id="affichage-services">
            <?php foreach ($service_table as $service): ?>
                <section class="sectionDynamique" id="Section_<?php echo htmlspecialchars($service['nom'], ENT_QUOTES, 'UTF-8'); ?>">
                    <h1 class="sectionDynamique3"><?php echo nl2br(htmlspecialchars_decode($service['nom'], ENT_QUOTES)); ?></h1>
                    <hr>
                    <div class="sectionDynamique2">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($service['imageService']); ?>" alt="Image du <?php echo htmlspecialchars($service['nom'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; height: auto;">
                        <p ><?php echo nl2br(htmlspecialchars_decode($service['description'], ENT_QUOTES)); ?></p>
                    </div>
                </section>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>
