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

//Chargement des tables et mise en variable
$stmt = $pdo->prepare('SELECT * FROM habitat');
$stmt->execute();
$habitat_table = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM animaux');
$stmt->execute();
$animaux_table = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM rapportveterinaire');
$stmt->execute();
$rapportveterinaire_table = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM nourriture');
$stmt->execute();
$nourriture_table = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM service');
$stmt->execute();
$service_table = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="/Site/Css/veterinaire.css">
    <title>Espace Vétérinaire - Gestion des habitats et animaux</title>
</head>
<body>

    <!-- Menu Navigation et Banniere -->
    <header>
        <!-- Section Banniere -->
        <section id="Section_Banniere">
            <div>
                <h1 class="Titre_Banniere">Espace Vétérinaire</h1>
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
                            // Vérifier si l'utilisateur est connecté et s'il a le rôle "Veterinaire"
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Veterinaire"|| $_SESSION['roleUtilisateur'] == "Admin")) {
                                // L'utilisateur est connecté en tant que vétérinaire, on affiche son espace
                                echo '<li class="page_navigante">Espace Veto</li>';
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

<!-- Section Gestion des Animaux et Habitats -->
<main>  
<h1>Formulaire d'enregistrement des rapports et de l'alimentation des animaux</h1>
    <form action="" method="POST">
    <fieldset>
            <legend>Choisir un animal</legend>
            <label for="animal">Animal :</label>
            <select name="animal" id="animal" required onchange="remplirChampsId()">
                <option value="">-- Sélectionnez un animal --</option>
                <?php if (!empty($animaux_table)): ?>
                    <?php foreach ($animaux_table as $animal): ?>
                        <option value="<?= htmlspecialchars($animal['pidAnimal']); ?>">
                            <?= htmlspecialchars($animal['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">Aucun animal disponible</option>
                <?php endif; ?>
            </select>
        </fieldset>
        
        <fieldset>
            <legend>Rapport Vétérinaire</legend>
            <label for="rapportVeterinaireAnimal"></label>
            <input type="number" name="rapportVeterinaireAnimal" id="rapportVeterinaireAnimal" readonly hidden>
            
            <label for="jour">Jour (Date) :</label>
            <input type="date" name="jour" id="jour">

            <label for="observation">Observation :</label>
            <textarea name="observation" id="observation" rows="4"></textarea>
        </fieldset>
        
        <fieldset>
            <legend>Nourriture</legend>
            <label for="nourritureAnimal"></label>
            <input type="number" name="nourritureAnimal" id="nourritureAnimal" readonly hidden>

            <label for="typeAlimentation">Type d'alimentation :</label>
            <input type="text" name="typeAlimentation" id="typeAlimentation" maxlength="100">

            <label for="grammage">Grammage (en grammes) :</label>
            <input type="number" step="0.01" name="grammage" id="grammage">

            <label for="dateAlimentation">Date de l'alimentation :</label>
            <input type="date" name="dateAlimentation" id="dateAlimentation">
        </fieldset>

        <fieldset>
            <legend>État de Santé de l'Animal</legend>
            <label for="etatDeSante">État de Santé :</label>
            <input type="text" name="etatDeSante" id="etatDeSante" maxlength="255">
        </fieldset>
        
        <button type="submit" name="enregistrer">Enregistrer</button>
    </form>


<?php
// Vérifier que le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enregistrer'])) {
    // Récupérer les valeurs des champs du formulaire
    $rapportVeterinaireAnimal = $_POST['rapportVeterinaireAnimal'];
    $jour = $_POST['jour'];
    $observation = htmlspecialchars($_POST['observation']);
    $nourritureAnimal = $_POST['nourritureAnimal'];
    $typeAlimentation = htmlspecialchars($_POST['typeAlimentation']);
    $grammage = $_POST['grammage'];
    $dateAlimentation = $_POST['dateAlimentation'];
    $etatDeSante = htmlspecialchars($_POST['etatDeSante']);

    try {
        // Vérifier l'existence du rapport vétérinaire
        $verifierRapport = $pdo->prepare("SELECT COUNT(*) FROM rapportveterinaire WHERE idRapport = ?");
        $verifierRapport->execute([$rapportVeterinaireAnimal]);
        $rapportExiste = $verifierRapport->fetchColumn();

        if ($rapportExiste) {
            // Mise à jour du rapport vétérinaire
            $miseAJourRapport = $pdo->prepare("UPDATE rapportveterinaire SET jour = ?, observation = ? WHERE idRapport = ?");
            $miseAJourRapport->execute([$jour, $observation, $rapportVeterinaireAnimal]);
        } else {
            // Insertion du rapport vétérinaire
            $insererRapport = $pdo->prepare("INSERT INTO rapportveterinaire (idRapport, jour, observation) VALUES (?, ?, ?)");
            $insererRapport->execute([$rapportVeterinaireAnimal, $jour, $observation]);
        }

        // Vérifier l'existence de l'entrée de nourriture
        $verifierNourriture = $pdo->prepare("SELECT COUNT(*) FROM nourriture WHERE idAlimentation = ?");
        $verifierNourriture->execute([$nourritureAnimal]);
        $nourritureExiste = $verifierNourriture->fetchColumn();

        if ($nourritureExiste) {
            // Mise à jour de la nourriture
            $miseAJourNourriture = $pdo->prepare("UPDATE nourriture SET typeAlimentation = ?, grammage = ?, dateAlimentation = ? WHERE idAlimentation = ?");
            $miseAJourNourriture->execute([$typeAlimentation, $grammage, $dateAlimentation, $nourritureAnimal]);
        } else {
            // Insertion de la nourriture
            $insererNourriture = $pdo->prepare("INSERT INTO nourriture (idAlimentation, typeAlimentation, grammage, dateAlimentation) VALUES (?, ?, ?, ?)");
            $insererNourriture->execute([$nourritureAnimal, $typeAlimentation, $grammage, $dateAlimentation]);
        }

        // Mise à jour de l'état de santé dans la table animaux
        $miseAJourEtat = $pdo->prepare("UPDATE animaux SET etatDeSante = ? WHERE pidAnimal = ?");
        $miseAJourEtat->execute([$etatDeSante, $rapportVeterinaireAnimal]);

        echo "<p>Les données ont été mises à jour avec succès !</p>";
    } catch (PDOException $e) {
        echo "<p>Erreur : " . $e->getMessage() . "</p>";
    }
}
?>

        <!-- Formulaire séparé pour l'avis sur l'habitat -->
        <h1>Formulaire d'avis sur l'habitat</h1>
        <form action="" method="POST">
            <fieldset>
                <legend>Avis sur l'Habitat</legend>
                <label for="habitat">Habitat :</label>
                <select name="habitat" id="habitat">
                    <option value="">-- Sélectionnez un habitat --</option>
                    <?php if (!empty($habitat_table)): ?>
                        <?php foreach ($habitat_table as $habitat): ?>
                            <option value="<?= htmlspecialchars($habitat['pidHabitat']); ?>">
                                <?= htmlspecialchars($habitat['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">Aucun habitat disponible</option>
                    <?php endif; ?>
                </select>

                <label for="avisHabitat">Avis sur l'Habitat :</label>
                <textarea name="avisHabitat" id="avisHabitat" rows="4"></textarea>
            </fieldset>
            
            <button type="submit" name="enregistrerAvis">Enregistrer Avis</button>
        </form>
    </main>

    <?php

    // Traitement du formulaire d'avis sur l'habitat
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enregistrerAvis'])) {
        // Récupérer les valeurs des champs du formulaire d'avis sur l'habitat
        $habitat = $_POST['habitat'];
        $avisHabitat = htmlspecialchars($_POST['avisHabitat']);

        try {
            // Mise à jour de l'avis sur l'habitat
            $miseAJourAvisHabitat = $pdo->prepare("UPDATE habitat SET avisHabitat = ? WHERE pidHabitat = ?");
            $miseAJourAvisHabitat->execute([$avisHabitat, $habitat]);

            echo "<p>L'avis sur l'habitat a été enregistré avec succès !</p>";
        } catch (PDOException $e) {
            echo "<p>Erreur : " . $e->getMessage() . "</p>";
        }
    }
    ?>

    <script>
        function remplirChampsId() {
            const selectAnimal = document.getElementById('animal');
            const animalId = selectAnimal.value;
            
            document.getElementById('rapportVeterinaireAnimal').value = animalId;
            document.getElementById('nourritureAnimal').value = animalId;
        }
        function deconnexion() {
        // Redirige l'utilisateur vers deconnexion.php
        window.location.href = '/Site/Html/deconnexion.php';
        }
    </script>

</body>
</html>
