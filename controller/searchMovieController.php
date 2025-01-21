<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);
include "$racine/modele/searchMovieModel.php";
include "$racine/vue/vueSearchMovie.php";