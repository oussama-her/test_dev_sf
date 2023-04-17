<?php

namespace App\DataSource;


use Symfony\Contracts\HttpClient\ResponseInterface;

interface HttpClientInterface
{
    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface;
}
