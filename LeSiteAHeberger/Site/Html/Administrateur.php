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

function getTableData($pdo, $tableName) {
    $stmt = $pdo->prepare("SELECT * FROM $tableName");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$utilisateur_table = getTableData($pdo, 'utilisateur');
$service_table = getTableData($pdo, 'service');
$animaux_table = getTableData($pdo, 'animaux');
$habitat_table = getTableData($pdo, 'habitat');

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
    <link rel="stylesheet" href="/Site/Css/administrateur.css">
    <title>Espace Administrateur - Deus es Machina</title>
</head>
<body>

    <!-- Menu Navigation et Banniere -->
    <header>
        <!-- Section Banniere -->
        <section id="Section_Banniere">
            <div>
                <h1 class="Titre_Banniere">Espace Administrateur</h1>
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
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Veterinaire")) {
                                echo '<li><a href="/Site/Html/Veterinaire.php">Espace Veto</a></li>';
                            }
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Employe")) {
                                echo '<li><a href="/Site/Html/Employe.php">Espace Employé</a></li>';
                            } 
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && $_SESSION['roleUtilisateur'] == "Admin") {
                                echo '<li class="page_navigante">Espace Administrateur</li>';
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
            <form action="Administrateur.php" method="POST">
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

<main>  

<?php include 'afficher_compteur.php'; ?>

<!--Fonction Création Utilisateur-->
    <section id="creation-utilisateur">
        <h2>Création d'un nouvel utilisateur</h2>
        <form action="Administrateur.php" method="POST">
            <table>
                <tr>
                    <td><label for="nom">Nom :</label></td>
                    <td><input type="text" id="nom" name="nom" required></td>
                </tr>
                <tr>
                    <td><label for="prenom">Prénom :</label></td>
                    <td><input type="text" id="prenom" name="prenom" required></td>
                </tr>
                <tr>
                    <td><label for="nouveauEmail">Email :</label></td>
                    <td><input type="email" id="nouveauEmail" name="nouveauEmail" required autocomplete="off"></td>
                </tr>
                <tr>
                    <td><label for="motDePasse">Mot de passe :</label></td>
                    <td><input type="password" id="motDePasse" name="motDePasse" required></td>
                </tr>
                <tr>
                    <td><label for="roleUtilisateur">Rôle :</label></td>
                    <td>
                        <select id="roleUtilisateur" name="roleUtilisateur" required>
                            <option value="Veterinaire">Veterinaire</option>
                            <option value="Employe">Employe</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="statut">Statut :</label></td>
                    <td>
                        <select id="statut" name="statut" required>
                            <option value="actif">Actif</option>
                            <option value="inactif">Inactif</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><button type="submit" name="creerUtilisateur">Créer Utilisateur</button></td>
                </tr>
            </table>
        </form>
    </section>

    <?php
    // Fonctionnalité de création d'utilisateur
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['creerUtilisateur'])) {
        if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['nouveauEmail']) && !empty($_POST['motDePasse']) && !empty($_POST['roleUtilisateur']) && !empty($_POST['statut'])) {
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $nouveauEmail = htmlspecialchars($_POST['nouveauEmail']);
            $motDePasse = password_hash(htmlspecialchars($_POST['motDePasse']), PASSWORD_DEFAULT);
            $roleUtilisateur = htmlspecialchars($_POST['roleUtilisateur']);
            $statut = htmlspecialchars($_POST['statut']);

            // Insertion dans la table utilisateur
            $insertionUtilisateur = $pdo->prepare('INSERT INTO utilisateur (nom, prenom, roleUtilisateur, email, motDePasse, statut) VALUES (?, ?, ?, ?, ?, ?)');
            if ($insertionUtilisateur->execute(array($nom, $prenom, $roleUtilisateur, $nouveauEmail, $motDePasse, $statut))) {

                // Envoi de l'email au nouvel utilisateur
                $to = $nouveauEmail;
                $subject = "Bienvenue dans l'équipe d'Arcadia";
                $message = "Bonjour $prenom $nom,\n\nVotre compte a été créé avec succès. Vous pouvez maintenant vous rapprocher de l'administrateur pour connaître votre mot de passe.\n\nMerci de votre confiance,\nL'équipe d'Arcadia.";
                $headers = "From: contact@dbarcadia.site\r\n";
                $headers .= "Reply-To: contact@dbarcadia.site\r\n";

                if (mail($to, $subject, $message, $headers)) {
                    echo "<script type='text/javascript'>window.location.href='Administrateur.php';</script>";
                } else {
                    echo "<p>Erreur lors de l'envoi de l'email à l'utilisateur.</p>";
                }
            } else {
                echo "<p>Erreur lors de la création de l'utilisateur.</p>";
            }
        }
    }
    ?>
    
<!--Fonction Modification Utilisateur-->
<section id="modification-utilisateur">
    <h2>Modification d'un utilisateur</h2>
    <form action="Administrateur.php" method="POST">
        <table>
            <tr>
                <td><label for="utilisateurSelection">Sélectionnez un utilisateur :</label></td>
                <td>
                    <select id="utilisateurSelection" name="utilisateurSelection" required>
                        <option value="" selected>Sélectionnez un utilisateur</option>
                        <?php foreach ($utilisateur_table as $utilisateur): ?>
                            <?php if ($utilisateur['roleUtilisateur'] != 'Admin'): ?>
                                <option value="<?php echo htmlspecialchars($utilisateur['pidUtilisateur'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($utilisateur['nom'] . ' ' . $utilisateur['prenom'] . ' (' . $utilisateur['email'] . ')', ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="nom">Nom :</label></td>
                <td><input type="text" id="nom" name="nom" ></td>
            </tr>
            <tr>
                <td><label for="prenom">Prénom :</label></td>
                <td><input type="text" id="prenom" name="prenom" ></td>
            </tr>
            <tr>
                <td><label for="nouveauEmail">Email :</label></td>
                <td><input type="email" id="nouveauEmail" name="nouveauEmail"  autocomplete="off"></td>
            </tr>
            <tr>
                <td><label for="roleUtilisateur">Rôle :</label></td>
                <td>
                    <select id="roleUtilisateur" name="roleUtilisateur" >
                        <option value="Veterinaire">Veterinaire</option>
                        <option value="Employe">Employe</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="statut">Statut :</label></td>
                <td>
                    <select id="statut" name="statut" >
                        <option value="actif">Actif</option>
                        <option value="inactif">Inactif</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit" name="modifierUtilisateur">Modifier Utilisateur</button>
                    <button type="submit" name="supprimerUtilisateur" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">Supprimer Utilisateur</button>
                </td>
            </tr>
        </table>
    </form>
</section>

<?php
// Fonctionnalité de modification d'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifierUtilisateur'])) {
    if (!empty($_POST['utilisateurSelection'])) {
        $pidUtilisateur = htmlspecialchars($_POST['utilisateurSelection']);
        $nom = !empty($_POST['nom']) ? htmlspecialchars($_POST['nom']) : null;
        $prenom = !empty($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : null;
        $nouveauEmail = !empty($_POST['nouveauEmail']) ? htmlspecialchars($_POST['nouveauEmail']) : null;
        $roleUtilisateur = !empty($_POST['roleUtilisateur']) ? htmlspecialchars($_POST['roleUtilisateur']) : null;
        $statut = !empty($_POST['statut']) ? htmlspecialchars($_POST['statut']) : null;

        $fieldsToUpdate = [];
        $valuesToUpdate = [];

        if ($nom !== null) {
            $fieldsToUpdate[] = 'nom = ?';
            $valuesToUpdate[] = $nom;
        }
        if ($prenom !== null) {
            $fieldsToUpdate[] = 'prenom = ?';
            $valuesToUpdate[] = $prenom;
        }
        if ($nouveauEmail !== null) {
            $fieldsToUpdate[] = 'email = ?';
            $valuesToUpdate[] = $nouveauEmail;
        }
        if ($roleUtilisateur !== null) {
            $fieldsToUpdate[] = 'roleUtilisateur = ?';
            $valuesToUpdate[] = $roleUtilisateur;
        }
        if ($statut !== null) {
            $fieldsToUpdate[] = 'statut = ?';
            $valuesToUpdate[] = $statut;
        }

        if (!empty($fieldsToUpdate)) {
            $valuesToUpdate[] = $pidUtilisateur;
            $updateQuery = 'UPDATE utilisateur SET ' . implode(', ', $fieldsToUpdate) . ' WHERE pidUtilisateur = ?';
            $updateUtilisateur = $pdo->prepare($updateQuery);

            $updateUtilisateur->execute($valuesToUpdate);
        }
    }
}

// Fonctionnalité de suppression d'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimerUtilisateur'])) {
    if (!empty($_POST['utilisateurSelection'])) {
        $pidUtilisateur = htmlspecialchars($_POST['utilisateurSelection']);
        $deleteQuery = 'DELETE FROM utilisateur WHERE pidUtilisateur = ?';
        $deleteUtilisateur = $pdo->prepare($deleteQuery);
        $deleteUtilisateur->execute([$pidUtilisateur]);
        echo "<script type='text/javascript'>window.location.href='Administrateur.php';</script>";
    }
}
?>





<!--Fonction Modification Service-->
<section id="modification-service">
        <h2>Gestion des Services</h2>
        <form action="Administrateur.php" method="POST" enctype="multipart/form-data">
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

        <form action="Administrateur.php" method="POST" enctype="multipart/form-data">
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
                window.location.href = 'Administrateur.php'; // Redirige vers la page après la suppression
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
                window.location.href = 'Administrateur.php'; // Redirige vers la page après la suppression
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
                    window.location.href = 'Administrateur.php'; // Redirige vers la page après la suppression
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
            window.location.href = 'Administrateur.php'; // Redirige vers la page après la suppression
            </script>";
        } else {
            echo "<p>Erreur lors de la suppression du service.</p>";
        }
    }
}
?>

<!--Fonction Modification Habitat-->
<section id="modification-habitat">
        <h2>Gestion des Habitats</h2>
        <form action="Administrateur.php" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <td><label for="nomHabitat">Nom de l'Habitat :</label></td>
                    <td><input type="text" id="nomHabitat" name="nomHabitat" required></td>
                </tr>
                <tr>
                    <td><label for="descriptionHabitat">Description :</label></td>
                    <td><textarea id="descriptionHabitat" name="descriptionHabitat" rows="5" required></textarea></td>
                </tr>
                <tr>
                    <td><label for="imageHabitat">Image de l'Habitat :</label></td>
                    <td><input type="file" id="imageHabitat" name="imageHabitat" required></td>
                </tr>
                <tr>
                    <td colspan="2"><button type="submit" name="ajouterHabitat">Ajouter Habitat</button></td>
                </tr>
            </table>
        </form>

        <form action="Administrateur.php" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <td><label for="habitatSelection">Sélectionnez un habitat à modifier ou supprimer :</label></td>
                    <td>
                        <select id="habitatSelection" name="habitatSelection" required>
                            <?php foreach ($habitat_table as $habitat): ?>
                                <option value="<?php echo htmlspecialchars($habitat['pidHabitat'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($habitat['nom'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="nouvelleDescriptionHabitat">Nouvelle Description :</label></td>
                    <td><textarea id="nouvelleDescriptionHabitat" name="nouvelleDescriptionHabitat" rows="5"></textarea></td>
                </tr>
                <tr>
                    <td><label for="nouvelleImageHabitat">Nouvelle Image de l'habitat :</label></td>
                    <td><input type="file" id="nouvelleImageHabitat" name="nouvelleImageHabitat"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" name="modifierHabitat">Modifier Habitat</button>
                        <button type="submit" name="supprimerHabitat">Supprimer Habitat</button>
                    </td>
                </tr>
            </table>
        </form>
    </section>

<?php
// Fonctionnalité de création et modification de habitat avec une image stockée en BLOB
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ajouterHabitat']) && !empty($_POST['nomHabitat']) && !empty($_POST['descriptionHabitat']) && isset($_FILES['imageHabitat'])) {
        $nomHabitat = htmlspecialchars($_POST['nomHabitat'], ENT_QUOTES, 'UTF-8');
        $descriptionHabitat = htmlspecialchars($_POST['descriptionHabitat'], ENT_QUOTES, 'UTF-8');

        // Gestion de l'image de l'habitat (sauvegarde en BLOB)
        $imageFile = $_FILES['imageHabitat'];
        if ($imageFile['size'] > 0) {
            $imageData = file_get_contents($imageFile['tmp_name']);

            // Insertion dans la table habitat
            $insertionHabitat = $pdo->prepare('INSERT INTO habitat (nom, textedescription, imageHabitat) VALUES (?, ?, ?)');
            if ($insertionHabitat->execute(array($nomHabitat, $descriptionHabitat, $imageData))) {
                echo "<script type='text/javascript'>
                alert('Habitat ajouté avec succès !');
                window.location.href = 'Administrateur.php'; // Redirige vers la page après la suppression
                </script>";
            } else {
                echo "<p>Erreur lors de l'ajout de l'Habitat.</p>";
            }
        } else {
            echo "<p>Erreur : aucune image n'a été téléchargée.</p>";
        }
    }

    if (isset($_POST['modifierHabitat']) && !empty($_POST['habitatSelection'])) {
        $pidHabitat = intval($_POST['habitatSelection']);
        $nouvelleDescriptionHabitat = !empty($_POST['nouvelleDescriptionHabitat']) ? htmlspecialchars($_POST['nouvelleDescriptionHabitat'], ENT_QUOTES, 'UTF-8') : null;

        // Mise à jour de la description
        if ($nouvelleDescriptionHabitat) {
            $modificationHabitat = $pdo->prepare('UPDATE habitat SET textedescription = ? WHERE pidHabitat = ?');
            if ($modificationHabitat->execute(array($nouvelleDescriptionHabitat, $pidHabitat))) {
                echo "<script type='text/javascript'>
                alert('Habitat changé avec succès !');
                window.location.href = 'Administrateur.php'; // Redirige vers la page après la suppression
                </script>";
            } else {
                echo "<p>Erreur lors de la modification de la description de l'Habitat.</p>";
            }
        }

        // Mise à jour de l'image
        if (isset($_FILES['nouvelleImageHabitat']) && $_FILES['nouvelleImageHabitat']['size'] > 0) {
            $imageFile = $_FILES['nouvelleImageHabitat'];
            if ($imageFile['size'] > 0) {
                $imageData = file_get_contents($imageFile['tmp_name']);
                $modificationHabitat = $pdo->prepare('UPDATE habitat SET imageHabitat = ? WHERE pidHabitat = ?');
                if ($modificationHabitat->execute(array($imageData, $pidHabitat))) {
                    echo "<script type='text/javascript'>
                    alert('Habitat changé avec succès !');
                    window.location.href = 'Administrateur.php'; // Redirige vers la page après la suppression
                    </script>";
                } else {
                    echo "<p>Erreur lors de la modification de l'image de l'Habitat.</p>";
                }
            } else {
                echo "<p>Erreur lors du téléchargement de la nouvelle image de l'Habitat.</p>";
            }
        }

    }
    if (isset($_POST['supprimerHabitat']) && !empty($_POST['habitatSelection'])) {
        $pidHabitat = intval($_POST['habitatSelection']);

        // Suppression de l'habitat dans la table habitat
        $suppressionHabitat = $pdo->prepare('DELETE FROM habitat WHERE pidHabitat = ?');
        if ($suppressionHabitat->execute(array($pidHabitat))) {
            echo "<script type='text/javascript'>
            alert('Habitat supprimé avec succès !');
            window.location.href = 'Administrateur.php'; // Redirige vers la page après la suppression
            </script>";
        } else {
            echo "<p>Erreur lors de la suppression de l'Habitat.</p>";
        }
    }
}
?>


<?php
    // Vérifier si le formulaire est soumis
    if (isset($_POST['ajouter_animal'])) {
        if (
            !empty($_POST['nom']) &&
            !empty($_POST['espace']) &&
            !empty($_POST['age']) &&
            !empty($_POST['etatDeSante']) &&
            !empty($_POST['animauxHabitat']) &&
            isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK
        ) {
            $nom = htmlspecialchars($_POST['nom']);
            $espace = htmlspecialchars($_POST['espace']);
            $age = (int)$_POST['age'];
            $etatDeSante = htmlspecialchars($_POST['etatDeSante']);
            $animauxHabitat = (int)$_POST['animauxHabitat'];
            $image = file_get_contents($_FILES['image']['tmp_name']);

            // Vérifier si l'habitat existe
            $checkHabitat = $pdo->prepare("SELECT pidHabitat FROM habitat WHERE pidHabitat = ?");
            $checkHabitat->execute([$animauxHabitat]);

            if ($checkHabitat->rowCount() > 0) {
                // Requête d'insertion pour ajouter un nouvel animal
                $requete = "INSERT INTO animaux (nom, espace, age, etatDeSante, animauxHabitat, image) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($requete);

                if ($stmt->execute([$nom, $espace, $age, $etatDeSante, $animauxHabitat, $image])) {
                    echo "<script type='text/javascript'>
                    alert('Animal Ajouté avec succès !');
                    window.location.href = 'Administrateur.php'; // Redirige vers la page après la suppression
                    </script>";
                } else {
                    echo "Erreur : " . $stmt->errorInfo()[2];
                }
            } else {
                echo "Erreur : L'ID de l'habitat spécifié n'existe pas.";
            }
        } else {
            echo "Veuillez remplir tous les champs et télécharger une image valide.";
        }
    }

    // Supprimer un animal
    if (isset($_POST['supprimer_animal'])) {
        $animalId = (int)$_POST['animal_id'];

        // Vérifier si l'animal existe avant de le supprimer
        $checkAnimal = $pdo->prepare("SELECT pidAnimal FROM animaux WHERE pidAnimal = ?");
        $checkAnimal->execute([$animalId]);

        if ($checkAnimal->rowCount() > 0) {
            $deleteAnimal = $pdo->prepare("DELETE FROM animaux WHERE pidAnimal = ?");
            if ($deleteAnimal->execute([$animalId])) {
                echo "<script type='text/javascript'>
                alert('Animal supprimé avec succès !');
                window.location.href = 'Administrateur.php'; // Redirige vers la page après la suppression
                </script>";
            } else {
                echo "Erreur : " . $deleteAnimal->errorInfo()[2];
            }
        } else {
            echo "Erreur : L'animal spécifié n'existe pas.";
        }
    }

    // Modifier un animal
    if (isset($_POST['modifier_animal'])) {
        if (
            !empty($_POST['animal_id']) &&
            !empty($_POST['nom']) &&
            !empty($_POST['espace']) &&
            !empty($_POST['age']) &&
            !empty($_POST['etatDeSante']) &&
            !empty($_POST['animauxHabitat'])
        ) {
            $animalId = (int)$_POST['animal_id'];
            $nom = htmlspecialchars($_POST['nom']);
            $espace = htmlspecialchars($_POST['espace']);
            $age = (int)$_POST['age'];
            $etatDeSante = htmlspecialchars($_POST['etatDeSante']);
            $animauxHabitat = (int)$_POST['animauxHabitat'];
            $image = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = file_get_contents($_FILES['image']['tmp_name']);
            }

            // Vérifier si l'animal existe avant de le modifier
            $checkAnimal = $pdo->prepare("SELECT pidAnimal FROM animaux WHERE pidAnimal = ?");
            $checkAnimal->execute([$animalId]);

            if ($checkAnimal->rowCount() > 0) {
                // Requête de mise à jour pour modifier un animal
                if ($image) {
                    $requete = "UPDATE animaux SET nom = ?, espace = ?, age = ?, etatDeSante = ?, animauxHabitat = ?, image = ? WHERE pidAnimal = ?";
                    $stmt = $pdo->prepare($requete);
                    $params = [$nom, $espace, $age, $etatDeSante, $animauxHabitat, $image, $animalId];
                } else {
                    $requete = "UPDATE animaux SET nom = ?, espace = ?, age = ?, etatDeSante = ?, animauxHabitat = ? WHERE pidAnimal = ?";
                    $stmt = $pdo->prepare($requete);
                    $params = [$nom, $espace, $age, $etatDeSante, $animauxHabitat, $animalId];
                }

                if ($stmt->execute($params)) {
                    echo "<script type='text/javascript'>
                    alert('Animal modifié avec succès !');
                    window.location.href = 'Administrateur.php'; // Redirige vers la page après la suppression
                    </script>";
                } else {
                    echo "Erreur : " . $stmt->errorInfo()[2];
                }
            } else {
                echo "Erreur : L'animal spécifié n'existe pas.";
            }
        } else {
            echo "Veuillez remplir tous les champs requis.";
        }
    }
    ?>

    <h2>Ajouter un nouvel animal</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <td><label for="nom">Nom de l'animal :</label></td>
                <td><input type="text" name="nom" id="nom" required></td>
            </tr>
            <tr>
                <td><label for="espace">Espace :</label></td>
                <td><input type="text" name="espace" id="espace" required></td>
            </tr>
            <tr>
                <td><label for="age">Âge :</label></td>
                <td><input type="number" name="age" id="age" required></td>
            </tr>
            <tr>
                <td><label for="etatDeSante">État de santé :</label></td>
                <td><input type="text" name="etatDeSante" id="etatDeSante" required></td>
            </tr>
            <tr>
    <td><label for="animauxHabitat">Habitat :</label></td>
    <td>
        <select name="animauxHabitat" id="animauxHabitat" required>
            <?php foreach ($habitat_table as $habitat) { ?>
                <option value="<?= $habitat['pidHabitat'] ?>"><?= htmlspecialchars($habitat['nom']) ?></option>
            <?php } ?>
        </select>
    </td>
</tr>
            <tr>
                <td><label for="image">Image de l'animal :</label></td>
                <td><input type="file" name="image" id="image" required></td>
            </tr>
            <tr>
                <td colspan="2"><button type="submit" name="ajouter_animal">Ajouter un animal</button></td>
            </tr>
        </table>
    </form>

    <h2>Modifier un animal</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <td><label for="animal_id">Choisir un animal :</label></td>
                <td>
                    <select name="animal_id" id="animal_id" required>
                        <?php foreach ($animaux_table as $animal) { ?>
                            <option value="<?= $animal['pidAnimal'] ?>"><?= htmlspecialchars($animal['nom']) ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="nom">Nom de l'animal :</label></td>
                <td><input type="text" name="nom" id="nom" required></td>
            </tr>
            <tr>
                <td><label for="espace">Espace :</label></td>
                <td><input type="text" name="espace" id="espace" required></td>
            </tr>
            <tr>
                <td><label for="age">Âge :</label></td>
                <td><input type="number" name="age" id="age" required></td>
            </tr>
            <tr>
                <td><label for="etatDeSante">État de santé :</label></td>
                <td><input type="text" name="etatDeSante" id="etatDeSante" required></td>
            </tr>
            <tr>
                <td><label for="animauxHabitat">Habitat :</label></td>
                <td>
                    <select name="animauxHabitat" id="animauxHabitat" required>
                        <?php foreach ($habitat_table as $habitat) { ?>
                            <option value="<?= $habitat['pidHabitat'] ?>"><?= htmlspecialchars($habitat['nom']) ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="image">Image de l'animal (optionnelle) :</label></td>
                <td><input type="file" name="image" id="image"></td>
            </tr>
            <tr>
                <td colspan="2"><button type="submit" name="modifier_animal">Modifier l'animal</button></td>
            </tr>
        </table>
    </form>

    <h2>Supprimer un animal</h2>
    <form action="" method="POST">
        <table>
            <tr>
                <td><label for="animal_id">Choisir un animal :</label></td>
                <td>
                    <select name="animal_id" id="animal_id" required>
                        <?php foreach ($animaux_table as $animal) { ?>
                            <option value="<?= $animal['pidAnimal'] ?>"><?= htmlspecialchars($animal['nom']) ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><button type="submit" name="supprimer_animal">Supprimer l'animal</button></td>
            </tr>
        </table>
    </form>
</main>

</main>
            <!-- Script Js affichage du menu Navigation -->
            <script src="/Script/Js/script.js"></script>
            
</body>
</html>
