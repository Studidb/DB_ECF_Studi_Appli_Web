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

// Augmenter la taille maximale des paquets
$pdo->exec("SET GLOBAL max_allowed_packet = 64 * 1024 * 1024;");

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
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Veterinaire" || $_SESSION['roleUtilisateur'] == "Admin")) {
                                echo '<li><a href="/Site/Html/Veterinaire.php">Espace Veto</a></li>';
                            }
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && ($_SESSION['roleUtilisateur'] == "Employe" || $_SESSION['roleUtilisateur'] == "Admin")) {
                                echo '<li>Espace Employé</li>';
                            } 
                            if (isset($_SESSION['email']) && isset($_SESSION['roleUtilisateur']) && $_SESSION['roleUtilisateur'] == "Admin") {
                                echo '<li class="page_navigante"><a href="/Site/Html/Administrateur.php">Espace Administrateur</a></li>';
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
        if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['nouveauEmail']) && !empty($_POST['roleUtilisateur']) && !empty($_POST['statut'])) {
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $nouveauEmail = htmlspecialchars($_POST['nouveauEmail']);
            $roleUtilisateur = htmlspecialchars($_POST['roleUtilisateur']);
            $statut = htmlspecialchars($_POST['statut']);

            // Insertion dans la table utilisateur
            $insertionUtilisateur = $pdo->prepare('INSERT INTO utilisateur (nom, prenom, roleUtilisateur, email, motDePasse, statut) VALUES (?, ?, ?, ?, ?, ?)');
            if ($insertionUtilisateur->execute(array($nom, $prenom, $roleUtilisateur, $nouveauEmail, '', $statut))) {
                echo "<p>Utilisateur créé avec succès ! L'utilisateur doit contacter l'administrateur pour obtenir son mot de passe.</p>";
            } else {
                echo "<p>Erreur lors de la création de l'utilisateur.</p>";
            }
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


    <!--Fonction Compte rendu véto-->
    <!--Fonction Click Animal-->
    
</main>
            <!-- Script Js affichage du menu Navigation -->
            <script src="/Script/Js/script.js"></script>
</body>
</html>
