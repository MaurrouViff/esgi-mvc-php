<?php
declare(strict_types=1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);

include "$racine/modele/users.php";
$classUsers = new Users();

include_once "$racine/modele/Authentification.php";

session_start(); // Démarre une session PHP pour stocker l'état de l'utilisateur.


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $motDePasse = $_POST['mot_de_passe'] ?? '';

    if ($action === 'register') {
        $message = Authentification::register($nom, $motDePasse);
    } elseif ($action === 'login') {
        $result = Authentification::login($nom, $motDePasse);
        if ($result['status'] === "success") {
            $_SESSION['user'] = $result['user'];
            $message = $result['message'];
        } else {
            $message = $result['message'];
        }
    } else {
        $message = "Action non reconnue.";
    }
}


$user = $_SESSION['user'] ?? null;

// Inclure les vues
$titre = "Connexion";
include "$racine/vue/vueHeader.php";
include "$racine/vue/vueConnexion.php";
include "$racine/vue/vueFooter.php";