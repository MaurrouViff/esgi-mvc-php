<?php
require_once(__DIR__ . '/../vendor/autoload.php');

use Dotenv\Dotenv;

class SearchMovieModel
{
    private $client;
    private $apiKey;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $this->client = new \GuzzleHttp\Client();
        $this->apiKey = $_ENV['TMDB_API_KEY'];
    }

    public function searchMovie(string $query)
    {
        $response = $this->client->request('GET', 'https://api.themoviedb.org/3/search/movie', [
            'query' => [
                'include_adult' => 'false',
                'language' => 'fr-FR',
                'page' => '1',
                'query' => $query
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'accept' => 'application/json',
            ],
            'verify' => false, // Disable SSL verification
        ]);

        $movies = json_decode($response->getBody(), true);

        // Check if the required fields are present
        foreach ($movies['results'] as $movie) {
            if (!isset($movie['poster_path'], $movie['title'], $movie['id'])) {
                throw new Exception('Missing required movie fields: poster_path, title, or id');
            }
        }

        // Return the results as JSON
        return json_encode(['results' => $movies['results']]);
    }
}
