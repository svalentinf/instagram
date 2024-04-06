<?php

namespace Svalentinf\InstagramApi\Request;

use Svalentinf\InstagramApi\Request;

class ContentPublishLimitRequest extends Request
{

    public string $method = 'get';


    public string $action = 'content_publishing_limit';

    /**
     * Makes the raw body of the request.
     *
     */
    protected function makeBody(): void
    {
        $this->body = [];
    }
}