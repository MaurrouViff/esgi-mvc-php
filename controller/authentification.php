<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);

include_once "$racine/modele/Authentification.php";

// Récupération des données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$action = $_POST['action'] ?? '';
$nom = $_POST['nom'] ?? '';
$motDePasse = $_POST['mot_de_passe'] ?? '';

if ($action === 'register') {
$message = Authentification::register($nom, $motDePasse);
} elseif ($action === 'login') {
$message = Authentification::login($nom, $motDePasse);
} else {
$message = "Action non reconnue.";
}

echo $message;
}

// Inclure les vues
$titre = "Connexion";
include "$racine/vue/vueHeader.php";
include "$racine/vue/vueConnexion.php";
include "$racine/vue/vueFooter.php";