<?php
declare(strict_types=1);

require_once(__DIR__ . '/../vendor/autoload.php');

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MovieInfosModel
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
     * Fetch movie information from the API.
     *
     * @param int $query
     * @return string
     * @throws GuzzleException
     */
    public function MovieInfos(int $query = 0): string
    {
        $response = $this->client->request('GET', 'https://api.themoviedb.org/3/movie/' . $query, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'accept' => 'application/json',
            ],
            'verify' => false, // Disable SSL verification
        ]);

        $movie = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        // Check if the required fields are present
        if (!isset($movie['title'], $movie['overview'], $movie['vote_average'], $movie['release_date'], $movie['poster_path'], $movie['genres'])) {
            return json_encode(['error' => 'Missing required movie fields: title, overview, vote_average, release_date, poster_path, or genres'], JSON_THROW_ON_ERROR);
        }

        return json_encode($movie, JSON_THROW_ON_ERROR);
    }
}