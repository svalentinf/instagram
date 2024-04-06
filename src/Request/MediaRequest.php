<?php

namespace Svalentinf\InstagramApi\Request;

use Svalentinf\InstagramApi\Request;

class MediaRequest extends Request
{


    public string $action = 'media';

    /**
     * Makes the raw body of the request.
     *
     */
    protected function makeBody(): void
    {

        $this->body = $this->container->makeBody();
    }
}