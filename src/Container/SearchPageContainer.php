<?php

namespace Svalentinf\InstagramApi\Container;

class SearchPageContainer extends Container
{
    /**
     * @param string $query
     */
    public function __construct(string $query)
    {
        $this->body['q'] = $query;
        $this->body['fields'] = 'id,name,location,link';
    }

}