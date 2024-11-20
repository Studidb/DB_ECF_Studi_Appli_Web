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

//Chargement des tables et mise en variable
$stmt = $pdo->prepare('SELECT * FROM habitat');
$stmt->execute();
$habitat_table = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM animaux');
$stmt->execute();
$animaux_table = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet'>
    <link rel="stylesheet" href="/Site/Css/habitats.css?v=2">
    <link rel="stylesheet" href="/Site/Css/styles.css?v=2">
    <link rel="stylesheet" href="/Site/Css/stylesMobile.css?v=2">
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

        <!-- Section Marais -->
        <section id="Section_Marais">
            <h1><?php echo htmlspecialchars($habitat_table[0]["nom"]); ?></h1>
            <hr>
            <div>
            
                <img src="/Ressources/Images/Habitats/MaraisArcadia.png" alt="Image du Marais d'Arcadia" class="habitat_image alternativeA_border_color">
                <div class="animal2">
                    <p><?php echo htmlspecialchars($habitat_table[0]["textedescription"]); ?></p>
                    <div>
                        <h1><?php echo htmlspecialchars($animaux_table[0]["nom"]); ?></h1>
                        <hr>
                        <img src="/Ressources/Images/Animaux/FlamingoGeneral.png" alt="Flamant Rose" class="animal_image">
                        <div class="Cache">
                            <p><?php echo htmlspecialchars($animaux_table[0]["espace"]); ?></p>
                            <p><?php echo htmlspecialchars($animaux_table[0]["age"]); ?></p>
                            <p><?php echo htmlspecialchars($animaux_table[0]["etatDeSante"]); ?></p>
                        </div>
                    </div>
                    <h1><?php echo htmlspecialchars($animaux_table[1]["nom"]); ?></h1>
                    <hr>
                    <img src="/Ressources/Images/Animaux/CrocodileGeneral.png" alt="Crocodile" class="animal_image">
                    <div class="Cache">
                            <p><?php echo htmlspecialchars($animaux_table[1]["espace"]); ?></p>
                            <p><?php echo htmlspecialchars($animaux_table[1]["age"]); ?></p>
                            <p><?php echo htmlspecialchars($animaux_table[1]["etatDeSante"]); ?></p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Section Savane -->
        <section id="Section_Savane">
            <h1><?php echo htmlspecialchars($habitat_table[1]["nom"]); ?></h1>
            <hr>
            <div class="animal">
                <img src="/Ressources/Images/Habitats/SavaneArcadia.png" alt="Image de la Savane d'Arcadia" class="habitat_image alternativeB_border_color">
                <div class="animal2">
                    <h1><?php echo htmlspecialchars($animaux_table[2]["nom"]); ?></h1>
                    <hr>
                    <img src="/Ressources/Images/Animaux/LionGeneral.png" alt="Lion" class="animal_image">
                    <div class="Cache">
                            <p><?php echo htmlspecialchars($animaux_table[2]["espace"]); ?></p>
                            <p><?php echo htmlspecialchars($animaux_table[2]["age"]); ?></p>
                            <p><?php echo htmlspecialchars($animaux_table[2]["etatDeSante"]); ?></p>
                    </div>
                    <h1><?php echo htmlspecialchars($animaux_table[3]["nom"]); ?></h1>
                    <hr>
                    <img src="/Ressources/Images/Animaux/GirafeGeneral.png" alt="Girafe" class="animal_image">
                    <div class="Cache">
                            <p><?php echo htmlspecialchars($animaux_table[3]["espace"]); ?></p>
                            <p><?php echo htmlspecialchars($animaux_table[3]["age"]); ?></p>
                            <p><?php echo htmlspecialchars($animaux_table[3]["etatDeSante"]); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Jungle -->
        <section id="Section_Jungle">
            <h1><?php echo htmlspecialchars($habitat_table[2]["nom"]); ?></h1>
            <hr>
            <div class="animal">
                <img src="/Ressources/Images/Habitats/JungleArcadia.png" alt="Image de la Jungle d'Arcadia" class="habitat_image alternativeA_border_color">
                <div class="animal2">
                    <h1><?php echo htmlspecialchars($animaux_table[4]["nom"]); ?></h1>
                    <hr>   
                    <img src="/Ressources/Images/Animaux/SerpentGeneral.png" alt="Serpent" class="animal_image">
                    <div class="Cache">
                            <p><?php echo htmlspecialchars($animaux_table[4]["espace"]); ?></p>
                            <p><?php echo htmlspecialchars($animaux_table[4]["age"]); ?></p>
                            <p><?php echo htmlspecialchars($animaux_table[4]["etatDeSante"]); ?></p>
                    </div>
                    <h1><?php echo htmlspecialchars($animaux_table[5]["nom"]); ?></h1>
                    <hr>   
                    <img src="/Ressources/Images/Animaux/JaguarGeneral.png" alt="Jaguar" class="animal_image">
                    <div class="Cache">
                            <p><?php echo htmlspecialchars($animaux_table[5]["espace"]); ?></p>
                            <p><?php echo htmlspecialchars($animaux_table[5]["age"]); ?></p>
                            <p><?php echo htmlspecialchars($animaux_table[5]["etatDeSante"]); ?></p>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
