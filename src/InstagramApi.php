<?php

namespace Svalentinf\InstagramApi;

use Svalentinf\InstagramApi\Container\AudioMessage;
use Svalentinf\InstagramApi\Container\Contact\ContactName;
use Svalentinf\InstagramApi\Container\Contact\Phone;
use Svalentinf\InstagramApi\Container\ContactMessage;
use Svalentinf\InstagramApi\Container\Container;
use Svalentinf\InstagramApi\Container\Document\Document;
use Svalentinf\InstagramApi\Container\DocumentMessage;
use Svalentinf\InstagramApi\Container\EmptyContainer;
use Svalentinf\InstagramApi\Container\ImageMessage;
use Svalentinf\InstagramApi\Container\LocationMessage;
use Svalentinf\InstagramApi\Container\Media\MediaID;
use Svalentinf\InstagramApi\Container\MediaPublishContainer;
use Svalentinf\InstagramApi\Container\OptionsList\Action;
use Svalentinf\InstagramApi\Container\OptionsListMessage;
use Svalentinf\InstagramApi\Container\SearchPageContainer;
use Svalentinf\InstagramApi\Container\StickerMessage;
use Svalentinf\InstagramApi\Container\Template\Component;
use Svalentinf\InstagramApi\Container\TemplateMessage;
use Svalentinf\InstagramApi\Container\ImageContainer;
use Svalentinf\InstagramApi\Container\VideoContainer;
use Svalentinf\InstagramApi\Request\MediaPublishRequest;
use Svalentinf\InstagramApi\Request\MediaRequest;
use Svalentinf\InstagramApi\Request\RequestAudioMessage;
use Svalentinf\InstagramApi\Request\RequestContactMessage;
use Svalentinf\InstagramApi\Request\RequestDocumentMessage;
use Svalentinf\InstagramApi\Request\RequestImageMessage;
use Svalentinf\InstagramApi\Request\RequestLocationMessage;
use Svalentinf\InstagramApi\Request\RequestOptionsListMessage;
use Svalentinf\InstagramApi\Request\RequestStickerMessage;
use Svalentinf\InstagramApi\Request\RequestTemplateMessage;
use Svalentinf\InstagramApi\Request\ContentPublishLimitRequest;
use Svalentinf\InstagramApi\Request\RequestVideoMessage;
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
     * @var Client The WhatsApp Cloud Api client service.
     */
    protected Client $client;

    /**
     * @var int The WhatsApp Cloud Api client timeout.
     */
    protected ?int $timeout;

    /**
     * Instantiates a new InstagramApi super-class object.
     *
     * @param array $config
     *
     */
    public function __construct(array $config)
    {
        $config = array_merge([
            'instagram_user_id' => null,
            'access_token'      => '',
            'graph_version'     => static::DEFAULT_GRAPH_VERSION,
            'client_handler'    => null,
            'timeout'           => null,
        ], $config);

        $this->app = new InstagramApiApp($config['instagram_user_id'], $config['access_token']);
        $this->timeout = $config['timeout'];
        $this->client = new Client($config['graph_version'], $config['client_handler']);
    }

    public function getContentPublishingLimit(): Response
    {

        $request = new ContentPublishLimitRequest(
            new EmptyContainer(),
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    public function getSearchPage($q): Response
    {
        $request = new SearchPageRequest(
            container: new SearchPageContainer($q),
            access_token: $this->app->accessToken(),
            timeout: $this->timeout
        );

        return $this->client->sendRequest($request);
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
            $mediaId = $this->client->sendRequest($mediaRequest)->decodedBody()['id'];
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
                $mediaId = $this->client->sendRequest($mediaRequest)->decodedBody()['id'];
            }
        }

        $mediaRequest = new MediaPublishRequest(
            new MediaPublishContainer($mediaId ?? ''),
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->client->sendRequest($mediaRequest);
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
            $mediaId = $this->client->sendRequest($mediaRequest)->decodedBody()['id'];
//            $mediaId = 18303971710198963;
            $mediaRequest = new MediaPublishRequest(
                new MediaPublishContainer($mediaId),
                $this->app->accessToken(),
                $this->app->instagramUserId(),
                $this->timeout
            );

            return $this->client->sendRequest($mediaRequest);
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