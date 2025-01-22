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
            error_log("File not found: " . $filePath);
            echo 'File not found';
            return;
        }

        $jsonContent = file_get_contents($filePath);
        if ($jsonContent === false) {
            error_log("Failed to read file: " . $filePath);
            echo 'Failed to read file';
            return;
        }

        $data = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error: " . json_last_error_msg());
            echo 'JSON decode error: ' . json_last_error_msg();
            return;
        }

        $this->movies = $data['film'] ?? [];
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

    /**
     * @throws Exception
     */
    private function addFilmIfNotExists(int $movieId, array $movieDetails): void
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
                error_log("Failed to write to file: " . $filmFilePath);
                throw new Exception('Failed to write to film.json');
            }
        }
    }

    /**
     * @throws Exception
     */
    public function addToFavorites(int $movieId, array $movieDetails): void
    {
        $userId = $_SESSION['user']['id'] ?? null;
        $this->addFilmIfNotExists($movieId, $movieDetails);

        // Assuming the user is logged in and their ID is stored in the session
        if ($userId === null) {
            throw new Exception('User not logged in');
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
            throw new Exception('Failed to write to users.json');
        }
    }

    /**
     * @throws Exception
     */
    public function addToWatchLater(int $movieId, array $movieDetails): void
    {
        $userId = $_SESSION['user']['id'] ?? null;
        $this->addFilmIfNotExists($movieId, $movieDetails);

        // Assuming the user is logged in and their ID is stored in the session
        if ($userId === null) {
            throw new Exception('User not logged in');
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
            throw new Exception('Failed to write to users.json');
        }
    }

    /**
     * @throws Exception
     */
    public function markAsWatched(int $movieId, array $movieDetails): void
    {
        $userId = $_SESSION['user']['id'] ?? null;
        $this->addFilmIfNotExists($movieId, $movieDetails);

        // Assuming the user is logged in and their ID is stored in the session
        if ($userId === null) {
            throw new Exception('User not logged in');
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
            throw new Exception('Failed to write to users.json');
        }
    }

    /**
     * @throws Exception
     */
    public function rateMovie(int $movieId, int $rating): void
    {
        $userId = $_SESSION['user']['id'] ?? null;

        // Assuming the user is logged in and their ID is stored in the session
        if ($userId === null) {
            throw new Exception('User not logged in');
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
            throw new Exception('Failed to write to users.json');
        }
    }
}