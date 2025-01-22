<?php
declare(strict_types=1);
require_once(__DIR__ . '/../vendor/autoload.php');

use Dotenv\Dotenv;

class SearchMovieModel
{
    private \GuzzleHttp\Client $client;
    private mixed $apiKey;


    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $this->client = new \GuzzleHttp\Client();
        $this->apiKey = $_ENV['TMDB_API_KEY'];
    }

    public function searchMovie($query)
    {
        $response = $this->client->request('GET', 'https://api.themoviedb.org/3/search/movie?query=' . $query, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'accept' => 'application/json',
            ],
            'verify' => false, // Disable SSL verification
        ]);

        return $response->getBody();
    }
}

