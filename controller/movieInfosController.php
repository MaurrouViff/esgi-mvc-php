<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);

include "$racine/modele/MovieInfosModel.php";

$model = new MovieInfosModel();
$query = $_GET['id'] ?? '';

if (empty($query)) {
    $movie = ['error' => 'Missing required movie ID'];
} else {
    $movie = $model->MovieInfos((int)$query);
}

$titre = $movie['title'] ?? 'Unknown Title';
include "$racine/vue/vueHeader.php";
include "$racine/vue/vueMovieInfos.php";