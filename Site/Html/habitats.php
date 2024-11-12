<?php

$pdo = new PDO("mysql:host=localhost;dbname=base_test_connectivite", "root", "");

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
    <link rel="stylesheet" href="/Site/Css/habitats.css">
    <link rel="stylesheet" href="/Site/Css/styles.css">
    <link rel="stylesheet" href="/Site/Css/stylesMobile.css">
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

    <!-- Les différentes Sections de la page services -->
    <main>

        <!-- Section Marais -->
        <section id="Section_Marais">
            <h1><?php echo htmlspecialchars($habitat_table[0]["nom"]); ?></h1>
            <hr>
            <div>
            
                <img src="/Ressources/Images/Habitats/MaraisArcadia.png" alt="Image du Marais d'Arcadia" class="habitat_image alternativeA_border_color">
                <div class="animal">
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
            <div class="animal">>
                <img src="/Ressources/Images/Habitats/SavaneArcadia.png" alt="Image de la Savane d'Arcadia" class="habitat_image alternativeB_border_color">
                <div class="animal">
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
                <div class="animal">
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
