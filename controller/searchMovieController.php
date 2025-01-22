<?php
declare(strict_types=1);

session_start();

use GuzzleHttp\Exception\GuzzleException;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);

include "$racine/modele/SearchMovieModel.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $csrf_token = $_GET['csrf_token'] ?? '';
    if (empty($csrf_token) || $csrf_token !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    $model = new SearchMovieModel();
    $query = $_GET['content'] ?? '';

    $results = null; // Initialize the $results variable

try {
    $results = $model->searchMovie($query);
    $movies = json_decode($results, true); // Decode the JSON string to an array
} catch (GuzzleException $e) {
    $error = ['error' => $e->getMessage()];
}


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
}