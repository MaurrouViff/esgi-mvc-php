<?php
declare(strict_types=1);

use GuzzleHttp\Exception\GuzzleException;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);

include "$racine/modele/SearchMovieModel.php";

$model = new SearchMovieModel();
$query = $_GET['content'] ?? 'cars';

try {
    $results = $model->searchMovie($query);
    // Read the response body
    $results = $results->getContents();
} catch (GuzzleException $e) {
    $results = json_encode(['error' => 'An error occurred while fetching the movie data. Please try again later.']);
}
include "$racine/vue/vueHeader.php";
include "$racine/vue/vueSearchMovie.php";

