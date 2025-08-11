<?php

namespace AndreInocenti\SocialMediaUrlIdExtractor\Platforms;

use AndreInocenti\SocialMediaUrlIdExtractor\Contracts\PlatformExtractorInterface;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

class TikTokExtractor extends AbstractExtractor implements PlatformExtractorInterface
{
    protected function getPatterns(PlatformsCategoriesEnum $resourceType): array
    {
        // barra final opcional + query/fragment opcionais
        $suffix = '/?(?:[?#].*)?$';

        switch ($resourceType) {
            case PlatformsCategoriesEnum::VIDEO:
                return [
                    // Canônico: /@user/video/{id}
                    "~^(?:https?://)?(?:www\\.)?tiktok\\.com/@[^/]+/video/([0-9A-Za-z_-]+)" . $suffix . "~i",

                    // Encurtados
                    "~^(?:https?://)?vm\\.tiktok\\.com/([A-Za-z0-9_-]+)" . $suffix . "~i",
                    "~^(?:https?://)?vt\\.tiktok\\.com/([A-Za-z0-9_-]+)" . $suffix . "~i",
                    "~^(?:https?://)?(?:www\\.)?tiktok\\.com/t/([A-Za-z0-9_-]+)" . $suffix . "~i",

                    // Mobile legado: /v/{id}.html
                    "~^(?:https?://)?m\\.tiktok\\.com/v/([0-9]+)\\.html(?:[?#].*)?$~i",

                    // Embeds: /embed/{id} e /embed/v2/{id}
                    "~^(?:https?://)?(?:www\\.)?tiktok\\.com/embed/(?:v2/)?([0-9]+)" . $suffix . "~i",
                ];

            case PlatformsCategoriesEnum::USER:
            case PlatformsCategoriesEnum::PROFILE:
                return [
                    "~^(?:https?://)?(?:www\\.)?tiktok\\.com/@([A-Za-z0-9._]+)" . $suffix . "~i",
                ];

            default:
                return [];
        }
    }
}
