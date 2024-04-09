<?php

namespace Svalentinf\InstagramApi\Container;

class MediaPublishContainer extends Container
{
    /**
     * @param string $creationId
     */
    public function __construct(string $creationId)
    {
        $this->body['creation_id'] = $creationId;
    }

}