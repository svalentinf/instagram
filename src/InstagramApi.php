<?php

namespace Svalentinf\InstagramApi;

use Svalentinf\InstagramApi\Container\Container;
use Svalentinf\InstagramApi\Container\EmptyContainer;
use Svalentinf\InstagramApi\Container\MediaPublishContainer;
use Svalentinf\InstagramApi\Container\SearchPageContainer;
use Svalentinf\InstagramApi\Request\MediaPublishRequest;
use Svalentinf\InstagramApi\Request\MediaRequest;
use Svalentinf\InstagramApi\Request\MyAccountsRequest;
use Svalentinf\InstagramApi\Request\ContentPublishLimitRequest;
use Svalentinf\InstagramApi\Request\SearchPageRequest;

class InstagramApi
{
    /**
     * @const string Default Graph API version.
     */
    public const DEFAULT_GRAPH_VERSION = 'v19.0';

    /**
     * @var InstagramApiApp The InstagramApiApp entity.
     */
    protected InstagramApiApp $app;

    /**
     * @var ClientFacebook The Instagram Cloud Api client service.
     */
    protected ClientFacebook $clientFacebook;
    protected ClientInstagram | null $clientInstagram = null;

    /**
     * @var int The Instagram Cloud Api client timeout.
     */
    protected ?int $timeout;

    protected array $config = [];

    /**
     * Instantiates a new InstagramApi super-class object.
     *
     * @param array $config
     *
     */
    public function __construct(array $config)
    {
        $this->config = array_merge([
            'instagram_user_id' => null,
            'access_token'      => '',
            'graph_version'     => static::DEFAULT_GRAPH_VERSION,
            'client_handler'    => null,
            'timeout'           => null,
        ], $config);

        $this->app = new InstagramApiApp($this->config['instagram_user_id'], $this->config['access_token']);
        $this->timeout = $this->config['timeout'];

        $this->clientFacebook = new ClientFacebook($this->config['graph_version'], $this->config['client_handler']);
    }

    public function getClientFacebook(): ClientFacebook
    {
        return $this->clientFacebook;
    }

    public function getClientInstagram(): ClientInstagram
    {
        if (!$this->clientInstagram) {
            $this->clientInstagram = new ClientInstagram($this->config['graph_version']);
        }

        return $this->clientInstagram;
    }


    public function setInstagramUserId(string $instagram_user_id)
    {
        $this->app->setInstagramUserId($instagram_user_id);
    }

    public function getContentPublishingLimit(): Response
    {

        $request = new ContentPublishLimitRequest(
            new EmptyContainer(),
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->getClientFacebook()->sendRequest($request);
    }

    public function getSearchPage($q): Response
    {
        $request = new SearchPageRequest(
            container: new SearchPageContainer($q),
            access_token: $this->app->accessToken(),
            timeout: $this->timeout
        );

        return $this->getClientFacebook()->sendRequest($request);
    }

    public function getMyAccounts(): Response
    {
        $request = new MyAccountsRequest(
            container: new EmptyContainer(),
            access_token: $this->app->accessToken(),
            timeout: $this->timeout
        );

        return $this->getClientFacebook()->sendRequest($request);

    }

    public function exchangeAccessToken(string $clientSecret): Response
    {
        $request = new MyAccountsRequest(
            container: new EmptyContainer([
                'grant_type'    => 'ig_exchange_token',
                'client_secret' => $clientSecret,
            ]),
            access_token: $this->app->accessToken(),
            timeout: $this->timeout
        );
        $request->setAction('access_token');

        echo "<div><pre>" . __FILE__ . " " . __LINE__ . "\n\r";
        dump($request);
        echo "</pre></div>";

        return $this->getClientInstagram()->sendRequest($request);
    }

    public function extendAccessToken(string $clientSecret): Response
    {
        $request = new MyAccountsRequest(
            container: new EmptyContainer([
                'grant_type'    => 'ig_refresh_token',
                'client_secret' => $clientSecret,
            ]),
            access_token: $this->app->accessToken(),
            timeout: $this->timeout
        );
        $request->setAction('refresh_access_token');


        return $this->getClientInstagram()->sendRequest($request);
    }

    public function getInstagramBusinessAccountByPageId(string $id): Response
    {
        $request = new MyAccountsRequest(
            container: new EmptyContainer(['fields' => 'instagram_business_account']),
            access_token: $this->app->accessToken(),
            timeout: $this->timeout
        );
        $request->setAction($id);

        return $this->getClientFacebook()->sendRequest($request);

    }

    public function publishPost(array | Container $data = null): Response
    {
        if ($data instanceof Container) {
            $mediaRequest = new MediaRequest(
                $data,
                $this->app->accessToken(),
                $this->app->instagramUserId(),
                $this->timeout
            );
            $mediaId = $this->getClientFacebook()->sendRequest($mediaRequest)->decodedBody()['id'];
        } else {
            foreach ($data as $container) {
                //set it as
                $container->addToBody('is_carousel_item', 1);
                $mediaRequest = new MediaRequest(
                    $container,
                    $this->app->accessToken(),
                    $this->app->instagramUserId(),
                    $this->timeout
                );
                $mediaId = $this->getClientFacebook()->sendRequest($mediaRequest)->decodedBody()['id'];
            }
        }

        $mediaRequest = new MediaPublishRequest(
            new MediaPublishContainer($mediaId ?? ''),
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->getClientFacebook()->sendRequest($mediaRequest);
    }

    public function publishStory(array | Container $data = null): Response
    {
        if ($data instanceof Container) {
            $data->addToBody('media_type', 'STORIES');
            $mediaRequest = new MediaRequest(
                $data,
                $this->app->accessToken(),
                $this->app->instagramUserId(),
                $this->timeout
            );
            $mediaId = $this->getClientFacebook()->sendRequest($mediaRequest)->decodedBody()['id'];

            $mediaRequest = new MediaPublishRequest(
                new MediaPublishContainer($mediaId),
                $this->app->accessToken(),
                $this->app->instagramUserId(),
                $this->timeout
            );

            return $this->getClientFacebook()->sendRequest($mediaRequest);
        }
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

    /**
     * Returns the Instagram User Id.
     *
     * @return string
     */
    public function instagramUserId(): string
    {
        return $this->app->instagramUserId();
    }
}