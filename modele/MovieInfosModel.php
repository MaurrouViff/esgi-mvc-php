<?php
require_once(__DIR__ . '/../vendor/autoload.php');

use Dotenv\Dotenv;
use GuzzleHttp\Client;

class MovieInfosModel
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function MovieInfos(int $query = 0): false|string
    {
        $response = $this->client->request('GET', 'https://api.themoviedb.org/3/movie/' . $query, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'accept' => 'application/json',
            ],
            'verify' => false, // Disable SSL verification
        ]);
        $movie = json_decode($response->getBody(), true);
        // Check if the required fields are present
        if (!isset($movie['title'], $movie['overview'], $movie['vote_average'], $movie['release_date'], $movie['poster_path'], $movie['genres'])) {
            throw new Exception('Missing required movie fields: title, overview, vote_average, release_date, poster_path, or genres');
        }

        return json_encode($movie);
    }
}
