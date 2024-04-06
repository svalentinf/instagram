<?php

namespace Svalentinf\InstagramApi\Container;

class EmptyContainer extends Container
{
    /**
     * Creates a new message of type text.
     *
     */
    public function __construct()
    {
    }

    /**
     * Return the body of the text message.
     *
     * @return string
     */
    public function text(): string
    {
        return $this->text;
    }

}