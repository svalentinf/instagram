<?php

namespace Svalentinf\InstagramApi;

use Svalentinf\InstagramApi\Http\ClientHandler;
use Svalentinf\InstagramApi\Http\GuzzleClientHandler;

class Client
{
    /**
     * @const string Production Graph API URL.
     */
    public const BASE_GRAPH_URL = 'https://graph.facebook.com';

    /**
     * @var ClientHandler The HTTP client handler to send the request.
     */
    protected ClientHandler $handler;

    /**
     * @var string Graph API version used.
     */
    protected string $graph_version;

    /**
     * Creates a new HTTP Client.
     *
     * @param string             $graph_version
     * @param ClientHandler|null $handler
     */
    public function __construct(string $graph_version, ?ClientHandler $handler = null)
    {
        $this->handler = $handler ?? $this->defaultHandler();

        $this->graph_version = $graph_version;
    }

    /**
     * Return the original request that returned this response.
     *
     * @return Response Raw response from the server.
     *
     * @throws Svalentinf\InstagramApi\Response\ResponseException
     */
    public function sendRequest(Request $request): Response
    {

        $raw_response = $this->handler->send(
            $this->buildRequestUri($request),
            $request->encodedBody(),
            $request->headers(),
            $request->timeout(),
            $request->method ?? 'post',
        );

        $return_response = new Response(
            $request,
            $raw_response->body(),
            $raw_response->httpResponseCode(),
            $raw_response->headers()
        );

        if ($return_response->isError()) {
            $return_response->throwException();
        }

        return $return_response;
    }

    private function defaultHandler(): ClientHandler
    {
        return new GuzzleClientHandler();
    }

    private function buildBaseUri(): string
    {
        return self::BASE_GRAPH_URL . '/' . $this->graph_version;
    }

    private function buildRequestUri(Request $request): string
    {
        //Based on the request type instagram user might be optional
        //send body as shit!
        if ($request->instagramUserId()) {
            return $this->buildBaseUri() . '/' . $request->instagramUserId() . '/' . $request->action . "?" . http_build_query($request->body());;
        } else {

            return $this->buildBaseUri() . '/' . $request->action . "?" . http_build_query($request->body());;
        }
    }
}