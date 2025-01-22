<?php
declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);

include "$racine/modele/searchMovieModel.php";

$model = new SearchMovieModel();
$query = $_GET['content'] ?? '';

$results = null; // Initialize the $results variable

try {
    if (empty($query)) {
        throw new Exception('Missing search query');
    }
    $results = $model->searchMovie((string)$query);
    $movies = json_decode($results, true, 512, JSON_THROW_ON_ERROR); // Decode the JSON string to an array
} catch (Exception $e) {
    $movies = ['error' => $e->getMessage()];
}

include "$racine/vue/vueSearchMovie.php";