<?php

declare(strict_types=1);

require_once(__DIR__ . '/../vendor/autoload.php');

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FetchMovieModel
{
    private Client $client;
    private string $apiKey;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $this->client = new Client();
        $this->apiKey = $_ENV['TMDB_API_KEY'];
    }

    /**
     * Fetch popular movies from the API.
     *
     * @return array
     * @throws GuzzleException
     */
    public function FetchMovie(): array
    {
        $response = $this->client->request('GET', 'https://api.themoviedb.org/3/movie/popular', [
            'query' => [
                'include_adult' => 'false',
                'language' => 'fr-FR',
                'page' => '1',
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'accept' => 'application/json',
            ],
            'verify' => false, // Disable SSL verification
        ]);

        $movies = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        // Check if the required fields are present
        foreach ($movies['results'] as $movie) {
            if (!isset($movie['release_date'], $movie['title'], $movie['id'])) {
                throw new Exception('Missing required movie fields: poster_path, title, or id');
            }
        }
        
        // Return the movie data directly
        return $movies;
    }
}
