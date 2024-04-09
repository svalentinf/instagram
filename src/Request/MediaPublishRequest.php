<?php

namespace Svalentinf\InstagramApi\Request;

use Svalentinf\InstagramApi\Request;

class MediaPublishRequest extends Request
{


    public string $action = 'media_publish';

    /**
     * Makes the raw body of the request.
     *
     */
    protected function makeBody(): void
    {

        $this->body = $this->container->makeBody();
    }
}