<?php

namespace Svalentinf\InstagramApi;

use Svalentinf\InstagramApi\Message\ImageContainer;
use Svalentinf\InstagramApi\Request\RequestMediaImage;

class InstagramCloudApi
{
    /**
     * @const string Default Graph API version.
     */
    public const DEFAULT_GRAPH_VERSION = 'v17.0';

    /**
     * @var InstagramCloudApiApp The InstagramCloudApiApp entity.
     */
    protected InstagramCloudApiApp $app;

    /**
     * @var Client The Instagram Cloud Api client service.
     */
    protected Client $client;

    /**
     * @var int|null The Instagram Cloud Api client timeout.
     */
    protected ?int $timeout;

    /**
     * Instantiates a new InstagramCloudApi super-class object.
     *
     * @param array $config
     *
     */
    public function __construct(array $config)
    {
        $config = array_merge([
            'access_token'   => '',
            'graph_version'  => static::DEFAULT_GRAPH_VERSION,
            'client_handler' => null,
            'timeout'        => null,
        ], $config);

        $this->app = new InstagramCloudApiApp($config['access_token']);
        $this->timeout = $config['timeout'];
        $this->client = new Client($config['graph_version'], $config['client_handler']);
    }


    /**
     * Post image on instagram.
     *
     *
     * @return Response
     */
    public function postImage(string $imageUrl): Response
    {
        $request = new RequestMediaImage(
            $this->app->accessToken(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    /**
     * Returns the Instagram Access Token.
     *
     * @return string
     */
    public function accessToken(): string
    {
        return $this->app->accessToken();
    }

}