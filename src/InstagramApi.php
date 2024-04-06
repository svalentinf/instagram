<?php

namespace Svalentinf\InstagramApi;

use Svalentinf\InstagramApi\Message\AudioMessage;
use Svalentinf\InstagramApi\Message\Contact\ContactName;
use Svalentinf\InstagramApi\Message\Contact\Phone;
use Svalentinf\InstagramApi\Message\ContactMessage;
use Svalentinf\InstagramApi\Message\Document\Document;
use Svalentinf\InstagramApi\Message\DocumentMessage;
use Svalentinf\InstagramApi\Message\ImageMessage;
use Svalentinf\InstagramApi\Message\LocationMessage;
use Svalentinf\InstagramApi\Message\Media\MediaID;
use Svalentinf\InstagramApi\Message\OptionsList\Action;
use Svalentinf\InstagramApi\Message\OptionsListMessage;
use Svalentinf\InstagramApi\Message\StickerMessage;
use Svalentinf\InstagramApi\Message\Template\Component;
use Svalentinf\InstagramApi\Message\TemplateMessage;
use Svalentinf\InstagramApi\Message\TextMessage;
use Svalentinf\InstagramApi\Message\VideoMessage;
use Svalentinf\InstagramApi\Request\RequestAudioMessage;
use Svalentinf\InstagramApi\Request\RequestContactMessage;
use Svalentinf\InstagramApi\Request\RequestDocumentMessage;
use Svalentinf\InstagramApi\Request\RequestImageMessage;
use Svalentinf\InstagramApi\Request\RequestLocationMessage;
use Svalentinf\InstagramApi\Request\RequestOptionsListMessage;
use Svalentinf\InstagramApi\Request\RequestStickerMessage;
use Svalentinf\InstagramApi\Request\RequestTemplateMessage;
use Svalentinf\InstagramApi\Request\RequestContentPublishLimit;
use Svalentinf\InstagramApi\Request\RequestVideoMessage;

class InstagramApi
{
    /**
     * @const string Default Graph API version.
     */
    public const DEFAULT_GRAPH_VERSION = 'v17.0';

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
            'access_token' => '',
            'graph_version' => static::DEFAULT_GRAPH_VERSION,
            'client_handler' => null,
            'timeout' => null,
        ], $config);

        $this->app = new InstagramApiApp($config['instagram_user_id'], $config['access_token']);
        $this->timeout = $config['timeout'];
        $this->client = new Client($config['graph_version'], $config['client_handler']);
    }

    public function getContentPublishingLimit()
    {

        $request = new RequestContentPublishLimit(
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    /**
     * Sends a Whatsapp text message.
     *
     * @param string WhatsApp ID or phone number for the person you want to send a message to.
     * @param string The body of the text message.
     * @param bool Determines if show a preview box for URLs contained in the text message.
     *
     * @throws Response\ResponseException
     */
    public function sendTextMessage(string $to, string $text, bool $preview_url = false): Response
    {
        $message = new TextMessage($to, $text, $preview_url);
        $request = new RequestContentPublishLimit(
            $message,
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    /**
     * Sends a document uploaded to the WhatsApp Cloud servers by it Media ID or you also
     * can put any public URL of some document uploaded on Internet.
     *
     * @param  string   $to         WhatsApp ID or phone number for the person you want to send a message to.
     * @param  Document $document   Document to send. See documents accepted in the Message/Document folder.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendDocument(string $to, MediaID $document_id, string $name, ?string $caption): Response
    {
        $message = new DocumentMessage($to, $document_id, $name, $caption);
        $request = new RequestDocumentMessage(
            $message,
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    /**
     * Sends a message template.
     *
     * @param  string         $to              WhatsApp ID or phone number for the person you want to send a message to.
     * @param  string         $template_name   Name of the template to send.
     * @param  string         $language        Language code
     * @param  Component|null $component       Component parameters of a template
     *
     * @link https://developers.facebook.com/docs/whatsapp/api/messages/message-templates#supported-languages See language codes supported.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendTemplate(string $to, string $template_name, string $language = 'en_US', ?Component $components = null): Response
    {
        $message = new TemplateMessage($to, $template_name, $language, $components);
        $request = new RequestTemplateMessage(
            $message,
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    /**
     * Sends a document uploaded to the WhatsApp Cloud servers by it Media ID or you also
     * can put any public URL of some document uploaded on Internet.
     *
     * @param  string   $to         WhatsApp ID or phone number for the person you want to send a message to.
     * @param  MediaId $document_id WhatsApp Media ID or any Internet public link document.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendAudio(string $to, MediaID $document_id): Response
    {
        $message = new AudioMessage($to, $document_id);
        $request = new RequestAudioMessage(
            $message,
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    /**
     * Sends a document uploaded to the WhatsApp Cloud servers by it Media ID or you also
     * can put any public URL of some document uploaded on Internet.
     *
     * @param  string   $to          WhatsApp ID or phone number for the person you want to send a message to.
     * @param  string   $caption     Description of the specified image file.
     * @param  MediaId  $document_id WhatsApp Media ID or any Internet public link document.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendImage(string $to, MediaID $document_id, ?string $caption = ''): Response
    {
        $message = new ImageMessage($to, $document_id, $caption);
        $request = new RequestImageMessage(
            $message,
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    /**
     * Sends a document uploaded to the WhatsApp Cloud servers by it Media ID or you also
     * can put any public URL of some document uploaded on Internet.
     *
     * @param  string   $to     WhatsApp ID or phone number for the person you want to send a message to.
     * @param  MediaId  $document_id WhatsApp Media ID or any Internet public link document.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendVideo(string $to, MediaID $link, string $caption = ''): Response
    {
        $message = new VideoMessage($to, $link, $caption);
        $request = new RequestVideoMessage(
            $message,
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    /**
     * Sends a sticker uploaded to the WhatsApp Cloud servers by it Media ID or you also
     * can put any public URL of some document uploaded on Internet.
     *
     * @param  string   $to             WhatsApp ID or phone number for the person you want to send a message to.
     * @param  MediaId  $document_id    WhatsApp Media ID or any Internet public link document.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendSticker(string $to, MediaID $link): Response
    {
        $message = new StickerMessage($to, $link);
        $request = new RequestStickerMessage(
            $message,
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    /**
     * Sends a location
     *
     * @param  string   $to         WhatsApp ID or phone number for the person you want to send a message to.
     * @param  float    $longitude  Longitude position.
     * @param  float    $latitude   Latitude position.
     * @param  string   $name       Name of location sent.
     * @param  address  $address    Address of location sent.
     *
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendLocation(string $to, float $longitude, float $latitude, string $name = '', string $address = ''): Response
    {
        $message = new LocationMessage($to, $longitude, $latitude, $name, $address);
        $request = new RequestLocationMessage(
            $message,
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    /**
     * Sends a contact
     *
     * @param  string        $to    WhatsApp ID or phone number for the person you want to send a message to.
     * @param  ContactName   $name  The contact name object.
     * @param  Phone|null    $phone The contact phone number.
     *
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendContact(string $to, ContactName $name, Phone ...$phone): Response
    {
        $message = new ContactMessage($to, $name, ...$phone);
        $request = new RequestContactMessage(
            $message,
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    public function sendList(string $to, string $header, string $body, string $footer, Action $action): Response
    {
        $message = new OptionsListMessage($to, $header, $body, $footer, $action);
        $request = new RequestOptionsListMessage(
            $message,
            $this->app->accessToken(),
            $this->app->instagramUserId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    /**
     * Returns the Facebook Whatsapp Access Token.
     *
     * @return string
     */
    public function accessToken(): string
    {
        return $this->app->accessToken();
    }

    /**
     * Returns the Facebook Phone Number ID.
     *
     * @return string
     */
    public function instagramUserId(): string
    {
        return $this->app->instagramUserId();
    }
}