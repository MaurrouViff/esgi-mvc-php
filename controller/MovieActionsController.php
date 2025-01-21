<?php
require_once('../modele/MovieActionsModel.php');

header('Content-Type: application/json');

$model = new MovieActionsModel();

$input = json_decode(file_get_contents('php://input'), true);
$movieId = $input['movie_id'] ?? '';
$action = $input['action'] ?? '';

$response = ['success' => false, 'message' => 'Invalid request'];

if (!empty($movieId) && !empty($action)) {
    switch ($action) {
        case 'favorite':
            $model->addToFavorites($movieId);
            $response = ['success' => true, 'message' => 'Added to favorites'];
            break;
        case 'watch_later':
            $model->addToWatchLater($movieId);
            $response = ['success' => true, 'message' => 'Added to watch later'];
            break;
        case 'watched':
            $model->markAsWatched($movieId);
            $response = ['success' => true, 'message' => 'Marked as watched'];
            break;
    }
}

echo json_encode($response);
exit;