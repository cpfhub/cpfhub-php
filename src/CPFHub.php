<?php

namespace CPFHub;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CPFHub
{
    private const BASE_URL = 'https://api.cpfhub.io';
    private $client;
    private $apiKey;

    public function __construct(string $apiKey, array $config = [])
    {
        if (empty($apiKey)) {
            throw new \InvalidArgumentException('API Key is required. Get yours at https://app.cpfhub.io');
        }

        $this->apiKey = $apiKey;
        $this->client = new Client(array_merge([
            'base_uri' => self::BASE_URL,
            'headers' => [
                'x-api-key' => $this->apiKey,
                'Accept' => 'application/json',
            ],
            'timeout' => 10.0,
        ], $config));
    }

    public function lookup(string $cpf): array
    {
        $cleanCpf = preg_replace('/\D/', '', $cpf);
        if (strlen($cleanCpf) !== 11) {
            throw new \InvalidArgumentException('Invalid CPF format. Must have 11 digits.');
        }

        try {
            $response = $this->client->get("/cpf/{$cleanCpf}");
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('CPFHub API Error: ' . $e->getMessage(), $e->getCode());
        }
    }

    public function getQuota(): array
    {
        try {
            $response = $this->client->get('/mcp', [
                'query' => ['api_key' => $this->apiKey]
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('CPFHub API Error: ' . $e->getMessage(), $e->getCode());
        }
    }
}
