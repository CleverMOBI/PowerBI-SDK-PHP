<?php

namespace Tngnt\PBI\API;

use Tngnt\PBI\Client;
use Tngnt\PBI\Response;

class Embed
{
    const GENERATE_TOKEN_URL = 'https://api.powerbi.com/v1.0/myorg/GenerateToken';

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $data
     * @return Response
     */
    public function createEmbedToken(array $data)
    {
        $url = self::GENERATE_TOKEN_URL;

        $response = $this->client->request(Client::METHOD_POST, $url, $data);

        return $this->client->generateResponse($response);
    }
}
