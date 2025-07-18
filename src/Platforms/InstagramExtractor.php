<?php

namespace AndreInocenti\SocialMediaUrlIdExtractor\Platforms;

use AndreInocenti\SocialMediaUrlIdExtractor\Contracts\PlatformExtractorInterface;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

class InstagramExtractor extends AbstractExtractor implements PlatformExtractorInterface
{
    protected function getPatterns(PlatformsCategoriesEnum $resourceType): array
    {
        $suffix = '\/?(?:\?.*)?$';
        switch ($resourceType) {
            case PlatformsCategoriesEnum::USER:
            case PlatformsCategoriesEnum::PROFILE:
                return [
                    "/^(?:https?:\/\/)?(?:www\.)?instagram\.com\/([A-Za-z0-9._]+)" . $suffix . "/i"
                ];
            case PlatformsCategoriesEnum::POST:
                return [
                    "/^(?:https?:\/\/)?(?:www\.)?instagram\.com\/p\/([A-Za-z0-9_-]+)" . $suffix . "/i",
                ];
            case PlatformsCategoriesEnum::REEL:
                return [
                    "/^(?:https?:\/\/)?(?:www\.)?instagram\.com\/reel\/([A-Za-z0-9_-]+)" . $suffix . "/i",
                ];
            case PlatformsCategoriesEnum::IGTV:
                return [
                    "/^(?:https?:\/\/)?(?:www\.)?instagram\.com\/tv\/([A-Za-z0-9_-]+)" . $suffix . "/i",
                ];
            default:
                return [];
        }
    }
}
