<?php

namespace AndreInocenti\SocialMediaUrlIdExtractor\Platforms;

use AndreInocenti\SocialMediaUrlIdExtractor\Contracts\PlatformExtractorInterface;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

class XExtractor extends AbstractExtractor  implements PlatformExtractorInterface
{
    protected function getPatterns(PlatformsCategoriesEnum $resourceType): array
    {
        $suffix = '/?(?:\\?.*)?$';
        switch ($resourceType) {
            case PlatformsCategoriesEnum::USER:
            case PlatformsCategoriesEnum::PROFILE:
                return [
                    // e.g. https://x.com/username or https://twitter.com/user_name
                    "~^(?:https?://)?(?:www\\.|mobile\\.)?(?:twitter\\.com|x\\.com)/([A-Za-z0-9_]{1,15})" . $suffix . "~i"
                ];

            case PlatformsCategoriesEnum::POST:
                return [
                    "~^(?:https?://)?(?:www\\.|mobile\\.)?(?:twitter\\.com|x\\.com)/[^/]+/status/(\\d+)" . $suffix . "~i",
                    "~^(?:https?://)?(?:www\\.|mobile\\.)?(?:twitter\\.com|x\\.com)/i/web/status/(\\d+)" . $suffix . "~i",
                ];

            default:
                return [];
        }
    }
}