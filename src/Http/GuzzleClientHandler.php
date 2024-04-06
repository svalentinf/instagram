<?php

namespace Svalentinf\InstagramApi\Http;

use GuzzleHttp\Client;

class GuzzleClientHandler implements ClientHandler
{
    /**
     * @var Client The Guzzle client.
     */
    protected Client $guzzle_client;

    /**
     * @param Client|null The Guzzle client.
     */
    public function __construct(?Client $guzzle_client = null)
    {
        $this->guzzle_client = $guzzle_client ?: new Client();
    }

    /**
     * {@inheritDoc}
     *
     */
    public function send(string $url, string $body, array $headers, int $timeout, $method = 'post'): RawResponse
    {
        if (!in_array($method, ['get', 'post'])) {
            $method = 'post';
        }

        $raw_handler_response = $this->guzzle_client->$method($url, [
            'body'    => $body,
            'headers' => $headers,
            'timeout' => $timeout,
        ]);

        return new RawResponse(
            $raw_handler_response->getHeaders(),
            $raw_handler_response->getBody(),
            $raw_handler_response->getStatusCode()
        );
    }
}