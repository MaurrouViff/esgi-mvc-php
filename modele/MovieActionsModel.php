<?php

declare(strict_types=1);

session_start();

class MovieActionsModel
{
    private array $movies;

    public function __construct()
    {
        // Load the movies JSON data
        $filePath = '../modele/film.json';
        if (!file_exists($filePath)) {
            echo 'File not found';
            return;
        }

        $jsonContent = file_get_contents($filePath);
        if ($jsonContent === false) {
            echo 'Failed to read file';
            return;
        }

        $data = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'JSON decode error: ' . json_last_error_msg();
            return;
        }

        $this->movies = $data['film'] ?? [];
    }

    private function movieExists(int $movieId): bool
    {
        return !empty(array_filter($this->movies, fn($movie) => $movie['id'] == $movieId));
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
        $data['users'] = array_map(function ($user) use ($userId, $movieId) {
            if ($user['id'] == $userId) {
                $movieExists = array_filter($user['films'], fn($film) => $film['id'] == $movieId);
                if ($movieExists) {
                    $user['films'] = array_map(function ($film) use ($movieId) {
                        if ($film['id'] == $movieId) {
                            $film['isFavorite'] = $film['isFavorite'] == true ? false : true;
                        }
                        return $film;
                    }, $user['films']);
                } else {
                    $user['films'][] = [
                        'id' => $movieId,
                        'isFavorite' => true
                    ];
                }
            }
            return $user;
        }, $data['users']);

        // Save the updated users JSON data
        if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
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
        $data['users'] = array_map(function ($user) use ($userId, $movieId) {
            if ($user['id'] == $userId) {
                $movieExists = array_filter($user['films'], fn($film) => $film['id'] == $movieId);
                if ($movieExists) {
                    $user['films'] = array_map(function ($film) use ($movieId) {
                        if ($film['id'] == $movieId) {
                            $film['isWatchLater'] = $film['isWatchLater'] == true ? false : true;
                        }
                        return $film;
                    }, $user['films']);
                } else {
                    $user['films'][] = [
                        'id' => $movieId,
                        'isWatchLater' => true
                    ];
                }
            }
            return $user;
        }, $data['users']);

        // Save the updated users JSON data
        if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
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
        $data['users'] = array_map(function ($user) use ($userId, $movieId) {
            if ($user['id'] == $userId) {
                $movieExists = array_filter($user['films'], fn($film) => $film['id'] == $movieId);
                if ($movieExists) {
                    $user['films'] = array_map(function ($film) use ($movieId) {
                        if ($film['id'] == $movieId) {
                            $film['isWatched'] = $film['isWatched'] == true ? false : true;
                        }
                        return $film;
                    }, $user['films']);
                } else {
                    $user['films'][] = [
                        'id' => $movieId,
                        'isWatched' => true
                    ];
                }
            }
            return $user;
        }, $data['users']);

        // Save the updated users JSON data
        if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
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
        $data['users'] = array_map(function ($user) use ($userId, $movieId, $rating) {
            if ($user['id'] == $userId) {
                $movieExists = array_filter($user['films'], fn($film) => $film['id'] == $movieId);
                if ($movieExists) {
                    $user['films'] = array_map(function ($film) use ($movieId, $rating) {
                        if ($film['id'] == $movieId) {
                            $film['rating'] = $rating;
                        }
                        return $film;
                    }, $user['films']);
                } else {
                    $user['films'][] = [
                        'id' => $movieId,
                        'rating' => $rating
                    ];
                }
            }
            return $user;
        }, $data['users']);

        // Save the updated users JSON data
        if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
            return ['error' => 'Failed to write to users.json'];
        }

        return ['success' => true];
    }
}
