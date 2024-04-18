<?php

namespace Svalentinf\InstagramApi\Container;

abstract class Container
{

    protected array $body = [];

    /**
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