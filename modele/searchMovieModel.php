<?php
declare(strict_types=1);

require_once(__DIR__ . '/../vendor/autoload.php');

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SearchMovieModel
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
     * Search for movies using the API.
     *
     * @param string $query
     * @return string
     * @throws GuzzleException
     * @throws Exception
     */
    public function searchMovie(string $query): string
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

        $movies = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        // Check if the required fields are present
        foreach ($movies['results'] as $movie) {
            if (!isset($movie['poster_path'], $movie['title'], $movie['id'])) {
                throw new Exception('Missing required movie fields: poster_path, title, or id');
            }
        }

        // Return the results as JSON
        return json_encode(['results' => $movies['results']], JSON_THROW_ON_ERROR);
    }
}