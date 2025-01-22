<?php
declare(strict_types=1);

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
    $response = $model->MovieInfos((int)$query);
    if (isset($response['error'])) {
        $movie = $response;
    } else {
        try {
            $movie = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            $movie = ['error' => $e->getMessage()];
        }
    }
}

include "$racine/vue/vueHeader.php";
include "$racine/vue/vueMovieInfos.php";