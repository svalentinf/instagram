<?php

namespace Svalentinf\InstagramApi;

class InstagramApiApp
{
    /**
     * @const string The name of the environment variable that contains the app from phone number ID.
     */
    public const APP_FROM_PHONE_NUMBER_ENV_NAME = 'INSTAGRAM_API_USER_ID';

    /**
     * @const string The name of the environment variable that contains the app access token.
     */
    public const APP_TOKEN_ENV_NAME = 'INSTAGRAM_API_TOKEN';

    /**
     * @const string Instagram User Id.
     */
    protected string | null $instagram_user_id;

    /**
     * @const string Instagram Access Token.
     */
    protected string | null $access_token;

    /**
     *
     * @param string|null $instagram_user_id
     * @param string|null $access_token
     */
    public function __construct(string | null $instagram_user_id = null, string $access_token = null)
    {
        $this->loadEnv();

        $this->instagram_user_id = $instagram_user_id ?: $_ENV[static::APP_FROM_PHONE_NUMBER_ENV_NAME] ?? null;
        $this->access_token = $access_token ?: $_ENV[static::APP_TOKEN_ENV_NAME] ?? null;

        $this->validate($this->instagram_user_id, $this->access_token);
    }

    /**
     * Returns the Instagram Access Token.
     *
     * @return string
     */
    public function accessToken(): string
    {
        return $this->access_token;
    }

    /**
     * Returns the Instagram User Id.
     *
     * @return string|null
     */
    public function instagramUserId(): ?string
    {
        return $this->instagram_user_id;
    }

    public function setInstagramUserId(string $instagram_user_id): void
    {
        $this->instagram_user_id = $instagram_user_id;
    }

    private function validate(string | null $instagram_user_id, string | null $access_token): void
    {
        // validate by function type hinting
    }

    private function loadEnv(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->safeLoad();
    }
}