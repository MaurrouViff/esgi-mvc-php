<?php
declare(strict_types=1);

require_once('../modele/MovieActionsModel.php');

header('Content-Type: application/json');

$model = new MovieActionsModel();

$input = json_decode(file_get_contents('php://input'), true);
$movieId = $input['movie']['id'] ?? null;
$action = $input['action'] ?? null;
$movieDetails = $input['movie'] ?? null;
$rating = $movieDetails['rating'] ?? null;

$response = ['success' => false, 'message' => 'Invalid request'];

if (!empty($movieId) && !empty($action) && !empty($movieDetails)) {
    try {
        switch ($action) {
            case 'favorite':
                $result = $model->addToFavorites((int)$movieId, (array)$movieDetails);
                if (isset($result['error'])) {
                    $response = ['success' => false, 'message' => $result['error']];
                } else {
                    $response = ['success' => true, 'message' => 'Update favorite'];
                }
                break;
            case 'watch_later':
                $result = $model->addToWatchLater((int)$movieId, (array)$movieDetails);
                if (isset($result['error'])) {
                    $response = ['success' => false, 'message' => $result['error']];
                } else {
                    $response = ['success' => true, 'message' => 'Update watch later'];
                }
                break;
            case 'watched':
                $result = $model->markAsWatched((int)$movieId, (array)$movieDetails);
                if (isset($result['error'])) {
                    $response = ['success' => false, 'message' => $result['error']];
                } else {
                    $response = ['success' => true, 'message' => 'Update watched'];
                }
                break;
            case 'rate':
                if ($rating !== null) {
                    $result = $model->rateMovie((int)$movieId, (int)$rating);
                    if (isset($result['error'])) {
                        $response = ['success' => false, 'message' => $result['error']];
                    } else {
                        $response = ['success' => true, 'message' => 'Movie rated'];
                    }
                } else {
                    $response = ['success' => false, 'message' => 'Missing rating'];
                }
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