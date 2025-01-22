<?php
require_once(__DIR__ . '/../vendor/autoload.php');

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FetchMovieModel
{
    private Client $client;
    private mixed $apiKey;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $this->client = new Client();
        $this->apiKey = $_ENV['TMDB_API_KEY'];
    }

    /**
     * @throws GuzzleException
     */
    public function FetchMovie(): mixed
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

        $movie = json_decode($response->getBody(), true);

        // Return the movie data directly
        return $movie;
    }
}