<?php

namespace AndreInocenti\SocialMediaUrlIdExtractor\Platforms;

use AndreInocenti\SocialMediaUrlIdExtractor\Contracts\PlatformExtractorInterface;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

class LinkedInExtractor extends AbstractExtractor implements PlatformExtractorInterface
{
    protected function getPatterns(PlatformsCategoriesEnum $resourceType): array
    {
        $suffix = '/?(?:\\?.*)?$';
        switch ($resourceType) {
            case PlatformsCategoriesEnum::USER:
            case PlatformsCategoriesEnum::PROFILE:
                return [
                    "~^(?:https?://)?(?:www\\.)?linkedin\\.com/in/([A-Za-z0-9_-]+)" . $suffix . "~i",
                    "~^(?:https?://)?(?:www\\.)?linkedin\\.com/pub/([A-Za-z0-9_-]+)" . $suffix . "~i",
                    "~^(?:https?://)?lnkd\\.in/([A-Za-z0-9]+)" . $suffix . "~i",
                ];

            case PlatformsCategoriesEnum::COMPANY:
                return [
                    "~^(?:https?://)?(?:www\\.)?linkedin\\.com/company/([A-Za-z0-9_-]+)" . $suffix . "~i",
                ];

            case PlatformsCategoriesEnum::POST:
            case PlatformsCategoriesEnum::ACTIVITY:
                return [
                    "~^(?:https?://)?(?:www\.)?linkedin\.com/posts/[^/]+_([A-Za-z0-9_-]+)(?:/|$)(?:\?.*)?$~i",
                    "~^(?:https?://)?(?:www\.)?linkedin\.com/posts/[^/]+_([^/]+)(?:/|$)(?:\?.*)?$~i",
                    "~linkedin\\.com/posts/.+_([A-Za-z0-9_-]+)" . $suffix . "~i",
                    "~linkedin\\.com/feed/update/urn:li:activity:(\\d+)" . $suffix . "~i",
                ];
            default:
                return [];
        }
    }
}
