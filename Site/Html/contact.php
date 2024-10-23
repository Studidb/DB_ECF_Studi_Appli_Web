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
    <link rel="stylesheet" href="/Site/Css/contact.css">
    <link rel="stylesheet" href="/Site/Css/styles.css">
    <link rel="stylesheet" href="/Site/Css/stylesMobile.css">
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
