<?php

namespace Svalentinf\InstagramApi\Request;

use Svalentinf\InstagramApi\Request;

class SearchPageRequest extends Request
{

    public string $method = 'get';


    public string $action = 'search';

    /**
     * Makes the raw body of the request.
     *
     */
    protected function makeBody(): void
    {

        $this->body = $this->container->makeBody();
    }
}