<?php

namespace Svalentinf\InstagramApi\Request;

use Svalentinf\InstagramApi\Request;

class MyAccountsRequest extends Request
{

    public string $method = 'get';


    public string $action = 'me/accounts';

    /**
     * Makes the raw body of the request.
     *
     */
    protected function makeBody(): void
    {

        $this->body = $this->container->makeBody();
    }
}