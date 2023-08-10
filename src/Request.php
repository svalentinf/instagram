<?php

namespace Svalentinf\InstagramApi;

use Svalentinf\InstagramApi\Message\Message;

abstract class Request
{
    /**
     * @const int The timeout in seconds for a normal request.
     */
    public const DEFAULT_REQUEST_TIMEOUT = 60;

    /**
     * @var Message Instagram Message to be sent.
     */
    protected Message $message;

    /**
     * @var string The access token to use for this request.
     */
    protected string $access_token;

    /**
     * @var string Instagram Number Id from messages will sent.
     */
    protected string $instagram_user_id;

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

    public ?string $action = null;

    /**
     * Creates a new Request entity.
     *
     * @param Message $message
     * @param string  $access_token
     */
    public function __construct(Message $message, string $access_token, string $instagram_user_id, ?int $timeout = null)
    {
        $this->message = $message;
        $this->access_token = $access_token;
        $this->instagram_user_id = $instagram_user_id;
        $this->timeout = $timeout ?? static::DEFAULT_REQUEST_TIMEOUT;

        $this->prepareRequest();
        $this->encodeBody();
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
     * Return Instagram Number Id for this request.
     *
     * @return string
     */
    public function fromInstagramUserId(): string
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
     * @return void
     */
    abstract protected function prepareRequest(): void;

}