<?php

namespace Svalentinf\InstagramApi\Container;

class ImageContainer extends Container
{
    /**
     * @param string $image_url
     * @param bool   $is_carousel_item
     * @param string $caption
     * @param string $location_id
     * @param array  $user_tags
     * @param array  $product_tags
     */
    public function __construct(string $image_url, bool $is_carousel_item = false, string $caption = '', string $location_id = '', array $user_tags = [], array $product_tags = [])
    {

        $this->body['image_url'] = $image_url;
        $this->body['is_carousel_item'] = $is_carousel_item;
        if ($caption) {
            $this->body['caption'] = $caption;
        }
        if ($location_id) {
            $this->body['location_id'] = $location_id;
        }
        if (count($user_tags)) {
            $this->body['user_tags'] = $user_tags;
        }
        if (count($product_tags)) {
            $this->body['product_tags'] = $product_tags;
        }

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

//    private function assertTextIsValid(string $text): void
//    {
//        if (strlen($text) > self::MAXIMUM_LENGTH) {
//            throw new \LengthException('The maximun length for a message text is ' . self::MAXIMUM_LENGTH . ' characters');
//        }
//    }
}