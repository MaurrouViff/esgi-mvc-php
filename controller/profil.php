<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);
include_once "$racine/modele/Users.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['user'];
$classUsers = new Users($_SESSION['user']['id']);


$listeFilmIds = array_column($_SESSION['user']['films'], 'id');

// Lire le fichier film.json
$filmFilePath = dirname(__FILE__) . '/../modele/film.json';
$filmData = json_decode(file_get_contents($filmFilePath), true);

// Récupérer les noms des films
$listeFilmNoms = array_map(function ($filmId) use ($filmData) {
    foreach ($filmData['film'] as $film) {
        if ($film['id'] == $filmId) {
            return $film['titre'];
        }
    }
    return null;
}, $listeFilmIds);

// Filtrer les films non trouvés
$listeFilmNoms = array_filter($listeFilmNoms);


switch ($_POST['action'] ?? '') {
    case 'acceptFriend':
        $classUsers->acceptFriend($_POST['userId'], $_POST['requestId']);
        header('Location: ./?action=profil');
        break;
    case 'addFriend':
        $classUsers->addFriend($_POST['userId'], $_POST['friendId']);
        header('Location: ./?action=profil');
        break;
    case 'rejectFriend':
        $classUsers->rejectFriendRequest($_POST['userId'], $_POST['requestId']);
        header('Location: ./?action=profil');
        break;

    default:
        // No action or unknown action
        break;
}
$titre = "Profil";
include "$racine/vue/vueHeader.php";
include "$racine/vue/vueProfil.php";
include "$racine/vue/vueFooter.php";
