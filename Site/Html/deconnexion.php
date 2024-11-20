<?php
session_start(); // Démarre la session

// Supprime toutes les variables de session
$_SESSION = [];

// Détruit la session
session_destroy();

// Redirige vers la page d'accueil ou une autre page
header("Location: /Site/Html/index.php");
exit();
