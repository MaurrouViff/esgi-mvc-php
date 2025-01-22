<?php
declare(strict_types=1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);


$titre = "Accueil";
include "$racine/vue/vueHeader.php";
include "$racine/vue/vueAccueil.php";
include "$racine/vue/vueFooter.php";