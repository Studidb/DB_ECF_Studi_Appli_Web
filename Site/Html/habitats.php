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

//Chargement des tables et mise en variable
$stmt = $pdo->prepare('SELECT * FROM habitat');
$stmt->execute();
$habitat_table = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM animaux');
$stmt->execute();
$animaux_table = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM nourriture');
$stmt->execute();
$nourriture_table = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM rapportveterinaire');
$stmt->execute();
$rapportveterinaire_table = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (extension_loaded("mongodb")) {
    try {
        $manager = new MongoDB\Driver\Manager("mongodb+srv://twobrochcorp:OYe4FL8B4VF7DkAp@cluster0.bvu0w.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0");
    } catch (MongoDB\Driver\Exception\Exception $e) {
        die("Erreur lors de la connexion à MongoDB : " . $e->getMessage());
    }
} else {
    die("Extension MongoDB non activée. Vérifiez la configuration de PHP.");
}
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

        <!-- Script Js affichage du menu Navigation -->
        <script src="/Script/Js/script.js"></script>
    </header>

    <!-- Les différentes Sections de la page services -->
    <main>

<?php foreach ($habitat_table as $habitatIndex => $habitat) : ?>
    <!-- Section Habitat -->
    <section class="sectionDynamique" id="Section_<?php echo htmlspecialchars($habitat['nom']); ?>">
        <h1 class="sectionDynamique3"><?php echo htmlspecialchars($habitat['nom']); ?></h1>
        <hr>
        <div class="sectionDynamique2">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($habitat['imageHabitat']); ?>" alt="Image de <?php echo htmlspecialchars($habitat['nom']); ?>" class="habitat_image">
            <div class="animal2">
                <p><?php echo htmlspecialchars($habitat['textedescription']); ?></p>
                <p><?php echo htmlspecialchars($habitat['avisHabitat']); ?></p>

                <?php foreach ($animaux_table as $animalIndex => $animal) : ?>
                    <?php if ($animal['animauxHabitat'] == $habitat['pidHabitat']) : ?>
                        <!-- Section Animal -->
                        <div class="sectionDynamique" style="position: relative;">
                            <h1 class="sectionDynamique3"><?php echo htmlspecialchars($animal['nom']); ?></h1>
                            <hr>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($animal['image']); ?>" alt="Image de l'animal <?php echo htmlspecialchars($animal['nom']); ?>" class="animal_image" data-id="<?php echo htmlspecialchars($animal['pidAnimal']); ?>">
                            <div class="Cache TableauStyle">
                                <table>
                                    <tr>
                                        <th>Espece</th>
                                        <td><?php echo htmlspecialchars($animal['espace']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Âge</th>
                                        <td><?php echo htmlspecialchars($animal['age']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>État de santé</th>
                                        <td><?php echo htmlspecialchars($animal['etatDeSante']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Type d'alimentation</th>
                                        <td><?php echo htmlspecialchars($nourriture_table[$animalIndex]["typeAlimentation"]); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Grammage</th>
                                        <td><?php echo htmlspecialchars($nourriture_table[$animalIndex]["grammage"]); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Date d'alimentation</th>
                                        <td><?php echo htmlspecialchars($nourriture_table[$animalIndex]["dateAlimentation"]); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Jour du rapport vétérinaire</th>
                                        <td><?php echo htmlspecialchars($rapportveterinaire_table[$animalIndex]["jour"]); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Observation</th>
                                        <td><?php echo htmlspecialchars($rapportveterinaire_table[$animalIndex]["observation"]); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

            </div>
        </div>
    </section>
<?php endforeach; ?>

</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sélectionner toutes les images d'animaux
        const animalImages = document.querySelectorAll('.animal_image');

        // Ajouter un événement de clic à chaque image d'animal
        animalImages.forEach(image => {
            image.addEventListener('click', function () {
                // Obtenir l'id de l'animal à partir de l'attribut data-id
                const animalId = this.getAttribute('data-id');

                // Envoi des données avec AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_click_counter.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('animal_id=' + encodeURIComponent(animalId));

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log('Compteur de clics mis à jour pour l\'animal avec ID : ' + animalId);
                    }
                };
            });
        });
    });
</script>

</body>
</html>
