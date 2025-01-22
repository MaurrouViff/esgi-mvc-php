<?php

use GuzzleHttp\Exception\GuzzleException;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);

include "$racine/modele/searchMovieModel.php";

$model = new SearchMovieModel();
$query = $_GET['content'] ?? '';

$results = null; // Initialize the $results variable

try {
    $results = $model->searchMovie($query);
    $movies = json_decode($results, true); // Decode the JSON string to an array
} catch (GuzzleException $e) {
    $error = ['error' => $e->getMessage()];
}

include "$racine/vue/vueSearchMovie.php";