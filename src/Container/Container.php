<?php

namespace Svalentinf\InstagramApi\Container;

abstract class Container
{

    protected array $body = [];

    /**
     * Return the WhatsApp ID or phone number for the person you want to send a message to.
     *
     * @return array
     */
    public function makeBody(): array
    {
        return $this->body;
    }

    public function addToBody($key, $value)
    {
        return $this->body[$key] = $value;
    }
}