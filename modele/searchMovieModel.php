<?php
require_once('vendor/autoload.php');

use Dotenv\Dotenv;

class MovieModel {
    private $client;
    private $apiKey;

    public function __construct() {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $this->client = new \GuzzleHttp\Client();
        $this->apiKey = $_ENV['TMDB_API_KEY'];
    }

    public function searchMovie($query) {
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
        ]);

        return $response->getBody();
    }
}
?>