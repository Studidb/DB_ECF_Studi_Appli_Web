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
    <link rel="stylesheet" href="/Site/Css/contact.css?v=2">
    <link rel="stylesheet" href="/Site/Css/styles.css?v=2">
    <link rel="stylesheet" href="/Site/Css/stylesMobile.css?v=2">
    <title>Contact Arcadia</title>
</head>
<body>

    <!-- Menu Navigation et Banniere -->
    <header>

        <!-- Section Banniere -->
        <section id="Section_Banniere">
            <div>
                <h1 class="Titre_Banniere">Contact</h1>
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
                        <li><a href="/Site/Html/habitats.php">Habitats</a></li>
                        <li class="page_navigante">Contact</li>
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
    <!-- Section du formulaire de contact -->
    <main>
        <section id="Section_Contact">
            <h2>Contactez-nous</h2>
            <p>Si vous avez des questions, veuillez remplir le formulaire ci-dessous.</p>

            <!-- Formulaire de contact -->
            <form action="mailto:contact@zooarcadia.com" method="post" enctype="text/plain">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" placeholder="Titre de votre demande" required>

                <label for="description">Description :</label>
                <textarea id="description" name="description" rows="5" placeholder="Décrivez votre demande" required></textarea>

                <label for="email">Votre Email :</label>
                <input type="email" id="email" name="email" placeholder="Votre adresse email" required>

                <button type="submit">Envoyer</button>
            </form>
        </section>
    </main>

</body>
</html>
