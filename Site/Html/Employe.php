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
                            // Vérifier si l'utilisateur est connecté et s'il a le rôle "Veterinaire"
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Veterinaire")) {
                                // L'utilisateur est connecté en tant que vétérinaire, on affiche son espace
                                echo '<li><a href="/Site/Html/Veterinaire.php">Espace Veto</a></li>';
                            }
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Employe")) {
                                // L'utilisateur est connecté en tant qu'employé, on affiche son espace
                                echo '<li class="page_navigante">Espace Employé</li>';
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
        
        <button type="submit" name="enregistrer">Enregistrer</button>
    </form>
</main>

<?php
// Vérifier que le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enregistrer'])) {
    // Récupérer les valeurs des champs du formulaire
    $nourritureAnimal = $_POST['nourritureAnimal'];
    $typeAlimentation = htmlspecialchars($_POST['typeAlimentation']);
    $grammage = $_POST['grammage'];
    $dateAlimentation = $_POST['dateAlimentation'];

    try {
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

        echo "<p>Les données ont été mises à jour avec succès !</p>";
    } catch (PDOException $e) {
        echo "<p>Erreur : " . $e->getMessage() . "</p>";
    }
}
?>

<script>
    function remplirChampsId() {
        const selectAnimal = document.getElementById('animal');
        const animalId = selectAnimal.value;
        
        document.getElementById('nourritureAnimal').value = animalId;
    }
</script>

<!--Fonction Modification Service-->
<section id="modification-service">
        <h2>Gestion des Services</h2>
        <form action="Employe.php" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <td><label for="nomService">Nom du service :</label></td>
                    <td><input type="text" id="nomService" name="nomService" required></td>
                </tr>
                <tr>
                    <td><label for="descriptionService">Description :</label></td>
                    <td><textarea id="descriptionService" name="descriptionService" rows="5" required></textarea></td>
                </tr>
                <tr>
                    <td><label for="imageService">Image du service :</label></td>
                    <td><input type="file" id="imageService" name="imageService" required></td>
                </tr>
                <tr>
                    <td colspan="2"><button type="submit" name="ajouterService">Ajouter Service</button></td>
                </tr>
            </table>
        </form>

        <form action="Employe.php" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <td><label for="serviceSelection">Sélectionnez un service à modifier ou supprimer :</label></td>
                    <td>
                        <select id="serviceSelection" name="serviceSelection" required>
                            <?php foreach ($service_table as $service): ?>
                                <option value="<?php echo htmlspecialchars($service['pidService'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($service['nom'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="nouvelleDescriptionService">Nouvelle Description :</label></td>
                    <td><textarea id="nouvelleDescriptionService" name="nouvelleDescriptionService" rows="5"></textarea></td>
                </tr>
                <tr>
                    <td><label for="nouvelleImageService">Nouvelle Image du service :</label></td>
                    <td><input type="file" id="nouvelleImageService" name="nouvelleImageService"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" name="modifierService">Modifier Service</button>
                        <button type="submit" name="supprimerService">Supprimer Service</button>
                    </td>
                </tr>
            </table>
        </form>
    </section>

<?php
// Fonctionnalité de création et modification de service avec une image stockée en BLOB
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ajouterService']) && !empty($_POST['nomService']) && !empty($_POST['descriptionService']) && isset($_FILES['imageService'])) {
        $nomService = htmlspecialchars($_POST['nomService'], ENT_QUOTES, 'UTF-8');
        $descriptionService = htmlspecialchars($_POST['descriptionService'], ENT_QUOTES, 'UTF-8');

        // Gestion de l'image du service (sauvegarde en BLOB)
        $imageFile = $_FILES['imageService'];
        if ($imageFile['size'] > 0) {
            $imageData = file_get_contents($imageFile['tmp_name']);

            // Insertion dans la table service
            $insertionService = $pdo->prepare('INSERT INTO service (nom, description, imageService) VALUES (?, ?, ?)');
            if ($insertionService->execute(array($nomService, $descriptionService, $imageData))) {
                echo "<script type='text/javascript'>
                alert('Service ajouté avec succés !');
                window.location.href = 'Employe.php'; // Redirige vers la page après la suppression
                </script>";
            } else {
                echo "<p>Erreur lors de l'ajout du service.</p>";
            }
        } else {
            echo "<p>Erreur : aucune image n'a été téléchargée.</p>";
        }
    }

    if (isset($_POST['modifierService']) && !empty($_POST['serviceSelection'])) {
        $pidService = intval($_POST['serviceSelection']);
        $nouvelleDescriptionService = !empty($_POST['nouvelleDescriptionService']) ? htmlspecialchars($_POST['nouvelleDescriptionService'], ENT_QUOTES, 'UTF-8') : null;

        // Mise à jour de la description
        if ($nouvelleDescriptionService) {
            $modificationService = $pdo->prepare('UPDATE service SET description = ? WHERE pidService = ?');
            if ($modificationService->execute(array($nouvelleDescriptionService, $pidService))) {
                echo "<script type='text/javascript'>
                alert('Service changé avec succés !');
                window.location.href = 'Employe.php'; // Redirige vers la page après la suppression
                </script>";
            } else {
                echo "<p>Erreur lors de la modification de la description du service.</p>";
            }
        }

        // Mise à jour de l'image
        if (isset($_FILES['nouvelleImageService']) && $_FILES['nouvelleImageService']['size'] > 0) {
            $imageFile = $_FILES['nouvelleImageService'];
            if ($imageFile['size'] > 0) {
                $imageData = file_get_contents($imageFile['tmp_name']);
                $modificationService = $pdo->prepare('UPDATE service SET imageService = ? WHERE pidService = ?');
                if ($modificationService->execute(array($imageData, $pidService))) {
                    echo "<script type='text/javascript'>
                    alert('Service changé avec succés !');
                    window.location.href = 'Employe.php'; // Redirige vers la page après la suppression
                    </script>";
                } else {
                    echo "<p>Erreur lors de la modification de l'image du service.</p>";
                }
            } else {
                echo "<p>Erreur lors du téléchargement de la nouvelle image du service.</p>";
            }
        }

    }
    if (isset($_POST['supprimerService']) && !empty($_POST['serviceSelection'])) {
        $pidService = intval($_POST['serviceSelection']);

        // Suppression du service dans la table service
        $suppressionService = $pdo->prepare('DELETE FROM service WHERE pidService = ?');
        if ($suppressionService->execute(array($pidService))) {
            echo "<script type='text/javascript'>
            alert('Service supprimé avec succès !');
            window.location.href = 'Employe.php'; // Redirige vers la page après la suppression
            </script>";
        } else {
            echo "<p>Erreur lors de la suppression du service.</p>";
        }
    }
}
?>


</main>
            <!-- Script Js affichage du menu Navigation -->
            <script src="/Script/Js/script.js"></script>
</body>
</html>
