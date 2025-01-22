<?php

declare(strict_types=1);

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$racine = dirname(__FILE__, 2);

include "$racine/modele/searchMovieModel.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $csrf_token = $_GET['csrf_token'] ?? '';
    if (empty($csrf_token) || $csrf_token !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    $model = new SearchMovieModel();
    $query = $_GET['content'] ?? '';

    $results = null; // Initialize the $results variable

    if (empty($query)) {
        $movies = ['error' => 'Missing search query'];
    } else {
        try {
            $results = $model->searchMovie((string)$query);
            $movies = json_decode($results, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            $movies = ['error' => $e->getMessage()];
        }
    }

    include "$racine/vue/vueSearchMovie.php";
}
