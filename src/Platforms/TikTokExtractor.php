<?php

namespace AndreInocenti\SocialMediaUrlIdExtractor\Platforms;

use AndreInocenti\SocialMediaUrlIdExtractor\Contracts\PlatformExtractorInterface;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

class TikTokExtractor extends AbstractExtractor implements PlatformExtractorInterface
{
    protected function getPatterns(PlatformsCategoriesEnum $resourceType): array
    {
        $suffix = '/?(?:\\?.*)?$';
        switch ($resourceType) {
            case PlatformsCategoriesEnum::VIDEO:
                return [
                    "~^(?:https?://)?(?:www\\.)?tiktok\\.com/@[^/]+/video/([A-Za-z0-9_-]+)" . $suffix . "~i",
                    "~^(?:https?://)?vm\\.tiktok\\.com/([A-Za-z0-9_-]+)" . $suffix . "~i",
                    "~^(?:https?://)?m\\.tiktok\\.com/v/([A-Za-z0-9_-]+)" . $suffix . "~i",
                ];

            case PlatformsCategoriesEnum::USER:
            case PlatformsCategoriesEnum::PROFILE:
                return [
                    "~^(?:https?://)?(?:www\\.)?tiktok\\.com/@([A-Za-z0-9._]+)" . $suffix . "~i"
                ];

            default:
                return [];
        }
    }
}
