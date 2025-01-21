<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title><?= $titre ?></title>
</head>
<body>
<header>
    <nav class="navbar">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="./?action=accueil">Accueil</a>
            </li>
            <li class="nav-item">
                <a href="./?action=authentification"><?= isset($_SESSION['user']) ? 'Profil' : 'Connexion'; ?></a>
            </li>
        </ul>
    </nav>
</header>
