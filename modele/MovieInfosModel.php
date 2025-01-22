<?php
declare(strict_types=1);
require_once(__DIR__ . '/../vendor/autoload.php');

use Dotenv\Dotenv;
use GuzzleHttp\Exception\GuzzleException;

class MovieInfosModel
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

    /**
     * @throws GuzzleException
     */
    public function MovieInfos($query): \Psr\Http\Message\StreamInterface
    {
        $response = $this->client->request('GET', 'https://api.themoviedb.org/3/movie/' . $query, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'accept' => 'application/json',
            ],
            'verify' => false, // Disable SSL verification
        ]);

        return $response->getBody();
    }
}

