<?php
require_once('../modele/MovieActionsModel.php');

header('Content-Type: application/json');

$model = new MovieActionsModel();

$input = json_decode(file_get_contents('php://input'), true);
$movieId = $input['movie']['id'] ?? null;
$action = $input['action'] ?? null;
$movieDetails = $input['movie'] ?? null;

$response = ['success' => false, 'message' => 'Invalid request'];

if (!empty($movieId) && !empty($action) && !empty($movieDetails)) {
    try {
        switch ($action) {
            case 'favorite':
                $model->addToFavorites($movieId, $movieDetails);
                $response = ['success' => true, 'message' => 'Update favorite'];
                break;
            case 'watch_later':
                $model->addToWatchLater($movieId, $movieDetails);
                $response = ['success' => true, 'message' => 'Update watch later'];
                break;
            case 'watched':
                $model->markAsWatched($movieId, $movieDetails);
                $response = ['success' => true, 'message' => 'Update watched'];
                break;
            default:
                $response = ['success' => false, 'message' => 'Unknown action'];
                break;
        }
    } catch (Exception $e) {
        error_log($e->getMessage()); // Log the error message
        $response = ['success' => false, 'message' => $e->getMessage()];
    }
} else {
    $response = ['success' => false, 'message' => 'Missing movie ID, action, or movie details'];
}

echo json_encode($response);
exit;