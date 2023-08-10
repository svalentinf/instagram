<?php

namespace Svalentinf\InstagramApi;

class InstagramCloudApiApp
{
    /**
     * @const string The name of the environment variable that contains instagram user id.
     */
    public const APP_INSTAGRAM_USER_ID_ENV_NAME = 'INSTAGRAM_CLOUD_API_INSTAGRAM_USER_ID';

    /**
     * @const string The name of the environment variable that contains the app access token.
     */
    public const APP_TOKEN_ENV_NAME = 'INSTAGRAM_CLOUD_API_TOKEN';

    /**
     * @const string Instagram user ID.
     */
    protected string $instagram_user_id;

    /**
     * @const string Instagram Access Token.
     */
    protected string $access_token;

    /**
     * Sends a Instagram.
     *
     * @param string $instagram_user_id The Instagram user ID.
     * @param string $access_token The Instagram Access Token.
     *
     */
    public function __construct(?string $instagram_user_id = null, ?string $access_token = null)
    {
        $this->loadEnv();


        $this->instagram_user_id = $instagram_user_id ?: $_ENV[static::APP_INSTAGRAM_USER_ID_ENV_NAME] ?? null;
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
     * Returns the Instagram User ID.
     *
     * @return string
     */
    public function fromInstagramUserId(): string
    {
        return $this->instagram_user_id;
    }

    private function validate(string $instagram_user_id, string $access_token): void
    {
        // validate by function type hinting
    }

    private function loadEnv(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->safeLoad();
    }
}