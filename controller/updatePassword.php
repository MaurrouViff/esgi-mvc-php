<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);

session_start();

if (isset($_GET['action']) && $_GET['action'] == 'updatePassword' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once "$racine/modele/AccountManager.php";

    // Récupérer les informations du formulaire
    $username = $_POST['nom'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';

    // Vérifier que le nom d'utilisateur et le mot de passe sont valides
    if (!empty($username) && !empty($newPassword)) {
        // Appeler la méthode pour mettre à jour le mot de passe
        if (AccountManager::changePassword($username, $newPassword)) {
            header("Location: " . $_SERVER['HTTP_REFERER']); // Rediriger vers la page précédente
        } else {
            echo "Erreur : Impossible de changer le mot de passe.";
        }
    } else {
        echo "Erreur : Veuillez fournir un nom d'utilisateur et un mot de passe.";
    }
}
