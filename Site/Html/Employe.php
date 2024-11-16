<?php
// Connexion à la base de données MySQL
$host = 'localhost';
$dbname = 'base_test_connectivite';
$username = 'root';
$password = '';

// Connexion à la session
session_start();
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}

// Initialisation de la variable $utilisateur
$utilisateur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoi'])) {
    if (!empty($_POST['email']) && !empty($_POST['motDePasse'])) {
        $email = htmlspecialchars($_POST['email']);
        $motDePasse = htmlspecialchars($_POST['motDePasse']);

        $utilisateur = $pdo->prepare('SELECT * FROM utilisateur WHERE email = ?');
        $utilisateur->execute(array($email));

        if ($utilisateur->rowCount() > 0) {
            $userInfo = $utilisateur->fetch();
            if (password_verify($motDePasse, $userInfo['motDePasse'])) {
                $_SESSION['email'] = $email;
                $_SESSION['roleUtilisateur'] = $userInfo['roleUtilisateur'];
            }
        }
    }
}

// Vérification de la connexion dans le reste du code
$isUserConnected = isset($_SESSION['email']);

// Suppression d'un avis
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $suppression = $pdo->prepare('DELETE FROM messages WHERE id = ?');
    $suppression->execute(array($id));
}

// Validation d'un avis
if (isset($_GET['valider'])) {
    $id = intval($_GET['valider']);
    // Récupérer l'avis à valider
    $avis = $pdo->prepare('SELECT * FROM messages WHERE id = ?');
    $avis->execute(array($id));
    $avisData = $avis->fetch(PDO::FETCH_ASSOC);

    if ($avisData) {
        // Insérer l'avis dans la table messagesvalides
        $insertion = $pdo->prepare('INSERT INTO messagesvalides (pseudo, message) VALUES (?, ?)');
        $insertion->execute(array($avisData['pseudo'], $avisData['message']));

        // Supprimer l'avis de la table messages
        $suppression = $pdo->prepare('DELETE FROM messages WHERE id = ?');
        $suppression->execute(array($id));
    }
}

// Récupérer tous les avis depuis la base de données
$requete = $pdo->prepare('SELECT * FROM messages');
$requete->execute();
$avis = $requete->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les avis validés pour les afficher sur la page d'accueil
$requeteAvisValides = $pdo->prepare('SELECT * FROM messagesvalides');
$requeteAvisValides->execute();
$avisValides = $requeteAvisValides->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="/Site/Css/employe.css">
    <title>Espace Employé - Gestion des Avis</title>
</head>
<body>

    <!-- Menu Navigation et Banniere -->
    <header>
        <!-- Section Banniere -->
        <section id="Section_Banniere">
            <div>
                <h1 class="Titre_Banniere">Espace Employé</h1>
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
                        <li><a href="/Site/Html/contact.php">Contact</a></li>
                        <?php 
                            // Vérifier si l'utilisateur est connecté et s'il a le rôle "Veterinaire" ou "Admin"
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Veterinaire" || $_SESSION['roleUtilisateur'] == "Admin")) {
                                echo '<li><a href="/Site/Html/Veterinaire.php">Espace Veto</a></li>';
                            }
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Employe" || $_SESSION['roleUtilisateur'] == "Admin")) {
                                echo '<li class="page_navigante">Espace Employé</li>';
                            } 
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && $_SESSION['roleUtilisateur'] == "Admin") {
                                echo '<li><a href="/Site/Html/Administrateur.php">Espace Administrateur</a></li>';
                            } 
                        ?>
                    </ul>
                    <ul>        
                    <?php 
                        // Vérifier si l'utilisateur est connecté en utilisant la session
                        if (isset($_SESSION['email'])) {
                            echo '<button id="Connexion" onclick="deconnexion()">Déconnexion</button>';
                        } else {
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

<!-- Section Gestion des Avis -->
<main>  
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
                <?php foreach ($avis as $avi): ?>
                    <tr>
                        <td><?= htmlspecialchars($avi['pseudo']); ?></td>
                        <td><?= htmlspecialchars($avi['message']); ?></td>
                        <td>
                            <?php if (!isset($avi['valide']) || $avi['valide'] == 0): ?>
                                <a href="?valider=<?= $avi['id']; ?>" class="btn-valider">Valider</a>
                            <?php endif; ?>
                            <a href="?supprimer=<?= $avi['id']; ?>" class="btn-supprimer">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
            <!-- Script Js affichage du menu Navigation -->
            <script src="/Script/Js/script.js"></script>
</body>
</html>
