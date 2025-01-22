<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);

include "$racine/modele/MovieInfosModel.php";

$model = new MovieInfosModel();
$query = $_GET['id'] ?? '';

try {
    if (empty($query)) {
        throw new Exception('Missing required movie ID');
    }
    $response = $model->MovieInfos($query);
    $movie = json_decode($response, true);
} catch (Exception $e) {
    $movie = ['error' => $e->getMessage()];
}
include "$racine/vue/vueHeader.php";
include "$racine/vue/vueMovieInfos.php";
