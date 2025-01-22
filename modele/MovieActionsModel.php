<?php
session_start();

class MovieActionsModel
{
    private mixed $movies;

    public function __construct()
    {
        // Load the movies JSON data
        $filePath = '../modele/film.json';
        if (!file_exists($filePath)) {
            return 'File not found';
        }

        $jsonContent = file_get_contents($filePath);
        if ($jsonContent === false) {
            return 'Failed to read file';
        }

        $data = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return 'JSON decode error: ' . json_last_error_msg();
        }

        $this->movies = $data['film'] ?? [];
        return true;
    }

    private function movieExists(int $movieId): bool
    {
        foreach ($this->movies as $movie) {
            if ($movie['id'] == $movieId) {
                return true;
            }
        }
        return false;
    }

    private function addFilmIfNotExists(int $movieId, array $movieDetails): array
    {
        if (!$this->movieExists($movieId)) {
            // Add the movie to films.json
            $filmFilePath = '../modele/film.json';
            $filmJsonContent = file_get_contents($filmFilePath);
            $filmData = json_decode($filmJsonContent, true);

            $newFilm = [
                'id' => $movieId,
                'titre' => $movieDetails['title'],
                'description' => $movieDetails['description'],
                'image' => $movieDetails['image'],
                'durée' => $movieDetails['duration']
            ];

            $filmData['film'][] = $newFilm;

            if (file_put_contents($filmFilePath, json_encode($filmData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
                return ['error' => 'Failed to write to film.json'];
            }
        }
        return ['success' => true];
    }

    public function addToFavorites(int $movieId, array $movieDetails): array
    {
        $userId = $_SESSION['user']['id'] ?? null;
        $result = $this->addFilmIfNotExists($movieId, $movieDetails);
        if (isset($result['error'])) {
            return $result;
        }

        // Assuming the user is logged in and their ID is stored in the session
        if ($userId === null) {
            return ['error' => 'User not logged in'];
        }

        // Load the users JSON data
        $filePath = '../modele/users.json';
        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true);

        // Find the user and add the movie to their films list
        foreach ($data['users'] as &$user) {
            if ($user['id'] == $userId) {
                $movieExists = false;
                foreach ($user['films'] as &$film) {
                    if ($film['id'] == $movieId) {
                        $movieExists = true;
                        $film['isFavorite'] = true; // Update isFavorite if the movie already exists
                        break;
                    }
                }
                if (!$movieExists) {
                    $user['films'][] = [
                        'id' => $movieId,
                        'isFavorite' => true
                    ];
                }
                break;
            }
        }

        // Save the updated users JSON data
        if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
            error_log("Failed to write to file: " . $filePath);
            return ['error' => 'Failed to write to users.json'];
        }

        return ['success' => true];
    }

    public function addToWatchLater(int $movieId, array $movieDetails): array
    {
        $userId = $_SESSION['user']['id'] ?? null;
        $result = $this->addFilmIfNotExists($movieId, $movieDetails);
        if (isset($result['error'])) {
            return $result;
        }

        // Assuming the user is logged in and their ID is stored in the session
        if ($userId === null) {
            return ['error' => 'User not logged in'];
        }

        // Load the users JSON data
        $filePath = '../modele/users.json';
        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true);

        // Find the user and add the movie to their films list
        foreach ($data['users'] as &$user) {
            if ($user['id'] == $userId) {
                $movieExists = false;
                foreach ($user['films'] as &$film) {
                    if ($film['id'] == $movieId) {
                        $movieExists = true;
                        $film['isWatchLater'] = true; // Update isWatchLater if the movie already exists
                        break;
                    }
                }
                if (!$movieExists) {
                    $user['films'][] = [
                        'id' => $movieId,
                        'isWatchLater' => true
                    ];
                }
                break;
            }
        }

        // Save the updated users JSON data
        if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
            error_log("Failed to write to file: " . $filePath);
            return ['error' => 'Failed to write to users.json'];
        }

        return ['success' => true];
    }

    public function markAsWatched(int $movieId, array $movieDetails): array
    {
        $userId = $_SESSION['user']['id'] ?? null;
        $result = $this->addFilmIfNotExists($movieId, $movieDetails);
        if (isset($result['error'])) {
            return $result;
        }

        // Assuming the user is logged in and their ID is stored in the session
        if ($userId === null) {
            return ['error' => 'User not logged in'];
        }

        // Load the users JSON data
        $filePath = '../modele/users.json';
        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true);

        // Find the user and add the movie to their films list
        foreach ($data['users'] as &$user) {
            if ($user['id'] == $userId) {
                $movieExists = false;
                foreach ($user['films'] as &$film) {
                    if ($film['id'] == $movieId) {
                        $movieExists = true;
                        $film['isWatched'] = true; // Update isWatched if the movie already exists
                        break;
                    }
                }
                if (!$movieExists) {
                    $user['films'][] = [
                        'id' => $movieId,
                        'isWatched' => true
                    ];
                }
                break;
            }
        }

        // Save the updated users JSON data
        if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
            error_log("Failed to write to file: " . $filePath);
            return ['error' => 'Failed to write to users.json'];
        }

        return ['success' => true];
    }

    public function rateMovie(int $movieId, int $rating): array
    {
        $userId = $_SESSION['user']['id'] ?? null;

        // Assuming the user is logged in and their ID is stored in the session
        if ($userId === null) {
            return ['error' => 'User not logged in'];
        }

        // Load the users JSON data
        $filePath = '../modele/users.json';
        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true);

        // Find the user and add the rating to the movie in their films list
        foreach ($data['users'] as &$user) {
            if ($user['id'] == $userId) {
                foreach ($user['films'] as &$film) {
                    if ($film['id'] == $movieId) {
                        $film['rating'] = $rating; // Update rating if the movie already exists
                        break 2;
                    }
                }
                // If the movie does not exist in the user's list, add it with the rating
                $user['films'][] = [
                    'id' => $movieId,
                    'rating' => $rating
                ];
                break;
            }
        }

        // Save the updated users JSON data
        if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
            error_log("Failed to write to file: " . $filePath);
            return ['error' => 'Failed to write to users.json'];
        }

        return ['success' => true];
    }
}