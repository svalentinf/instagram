<?php

namespace Svalentinf\InstagramApi\Request;

use Svalentinf\InstagramApi\Request;

class RequestMediaImage extends Request
{

    public bool $is_carousel_item = false;
    public ?string $caption = null;

    /**
     * Makes the raw body of the request.
     *
     */
    protected function prepareRequest(): void
    {

        $this->action = 'media';

        $this->body = [
            'image_url'        => $this->message->messagingProduct(),
            'is_carousel_item' => $this->is_carousel_item,
            'caption'          => $this->caption,
            'location_id'      => $this->message->type(),
            'user_tags'        => $this->message->type(),
            'product_tags'     => $this->message->type(),
            'access_token'     => $this->accessToken(),
            'text'             => [
                'preview_url' => $this->message->previewUrl(),
                'body'        => $this->message->text(),
            ],
        ];
    }
}