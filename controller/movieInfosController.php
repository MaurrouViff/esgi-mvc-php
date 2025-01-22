<?php
declare(strict_types=1);

use GuzzleHttp\Exception\GuzzleException;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);

include "$racine/modele/MovieInfosModel.php";

$model = new MovieInfosModel();
$query = $_GET['id'] ?? 'cars';

try {
    $response = $model->MovieInfos($query);

    $movie = json_decode($response->getContents(), true);
} catch (GuzzleException $e) {
    $results = json_encode(['error' => 'An error occurred while fetching the movie data. Please try again later.']);
}
include "$racine/vue/vueHeader.php";
include "$racine/vue/vueMovieInfos.php";

