<?php

namespace App\Service\Utils;

use App\Service\Utils\HttpClientInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface as SymfonyClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class HttpClient implements HttpClientInterface
{
    private SymfonyClientInterface $httpClient;

    public function __construct(SymfonyClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        return $this->httpClient->request($method, $url,$options);
    }
}