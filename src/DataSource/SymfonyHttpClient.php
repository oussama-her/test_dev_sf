<?php

namespace App\DataSource;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SymfonyHttpClient implements HttpClientInterface
{
    /**
     * @var HttpClient
     */
    private $client;

    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        return $this->client->request($method, $url, $options);
    }
}
