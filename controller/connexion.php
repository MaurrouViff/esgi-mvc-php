<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);

$titre = "Connexion";
include "$racine/vue/vueHeader.php";
include "$racine/vue/vueConnexion.php";
include "$racine/vue/vueFooter.php";