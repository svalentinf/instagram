<?php

namespace Svalentinf\InstagramApi;

use Svalentinf\InstagramApi\Container\Container;

abstract class Request
{
    /**
     * @const int The timeout in seconds for a normal request.
     */
    public const DEFAULT_REQUEST_TIMEOUT = 60;


    /**
     * @var string The access token to use for this request.
     */
    protected string $access_token;

    /**
     * @var string Instagram user Id from messages will sent.
     */
    protected ?string $instagram_user_id = null;

    /**
     * The raw body request.
     *
     * @return array
     */
    protected array $body;

    /**
     * The raw encoded body request.
     *
     * @return string
     */
    protected string $encoded_body;

    /**
     * The timeout request.
     *
     * @return int
     */
    protected int $timeout;

    public string $method = 'post';

    public string $action = '';

    public Container $container;

    /**
     * Creates a new Request entity.
     *
     * @param Container $container
     * @param string    $access_token
     */
    public function __construct(Container $container, string $access_token, string $instagram_user_id = null, ?int $timeout = null)
    {
        $this->container = $container;
        $this->access_token = $access_token;
        $this->instagram_user_id = $instagram_user_id;
        $this->timeout = $timeout ?? static::DEFAULT_REQUEST_TIMEOUT;

        $this->makeBody();
        $this->encodeBody();
    }

    /**
     * Returns the raw body of the request.
     *
     * @return array
     */
    public function body(): array
    {
        return $this->body;
    }

    /**
     * Returns the body of the request encoded.
     *
     * @return string
     */
    public function encodedBody(): string
    {
        return $this->encoded_body;
    }

    /**
     * Return the headers for this request.
     *
     * @return array
     */
    public function headers(): array
    {
        return [
            'Authorization' => "Bearer $this->access_token",
            'Content-Type'  => 'application/json',
        ];
    }

    /**
     * Return the access token for this request.
     *
     * @return string
     */
    public function accessToken(): string
    {
        return $this->access_token;
    }

    /**
     * Return Instagram user Id for this request.
     *
     * @return string|null
     */
    public function instagramUserId(): ?string
    {
        return $this->instagram_user_id;
    }

    /**
     * Return the timeout for this request.
     *
     * @return int
     */
    public function timeout(): int
    {
        return $this->timeout;
    }

    /**
     * Makes the raw body of the request.
     *
     * @return array
     */
    abstract protected function makeBody(): void;

    /**
     * Encodes the raw body of the request.
     *
     * @return array
     */
    private function encodeBody(): void
    {
        $this->encoded_body = json_encode($this->body());
    }
}