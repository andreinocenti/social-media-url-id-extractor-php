<?php

namespace AndreInocenti\SocialMediaUrlIdExtractor\Platforms;

use AndreInocenti\SocialMediaUrlIdExtractor\Contracts\PlatformExtractorInterface;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

class InstagramExtractor extends AbstractExtractor implements PlatformExtractorInterface
{
    protected function getPatterns(PlatformsCategoriesEnum $resourceType): array
    {
        // barra final opcional + query/fragment opcionais
        $suffix = '\/?(?:[?#].*)?$';

        switch ($resourceType) {
            case PlatformsCategoriesEnum::USER:
            case PlatformsCategoriesEnum::PROFILE:
                return [
                    // username (perfil) – com ou sem www, com . e _
                    "/^(?:https?:\/\/)?(?:www\.)?instagram\.com\/([A-Za-z0-9._]+)" . $suffix . "/i",
                ];

            case PlatformsCategoriesEnum::POST:
                return [
                    // /p/{id}
                    "/^(?:https?:\/\/)?(?:www\.)?instagram\.com\/p\/([A-Za-z0-9_-]+)" . $suffix . "/i",
                ];

            case PlatformsCategoriesEnum::REEL:
                return [
                    // /reel/{id}
                    "/^(?:https?:\/\/)?(?:www\.)?instagram\.com\/reel\/([A-Za-z0-9_-]+)" . $suffix . "/i",
                ];

            case PlatformsCategoriesEnum::IGTV:
                return [
                    // /tv/{id}
                    "/^(?:https?:\/\/)?(?:www\.)?instagram\.com\/tv\/([A-Za-z0-9_-]+)" . $suffix . "/i",
                ];

            case PlatformsCategoriesEnum::STORY:
                return [
                    // /stories/{username}/{storyId}  → captura SOMENTE {storyId}
                    "/^(?:https?:\/\/)?(?:www\.)?instagram\.com\/stories\/(?:[A-Za-z0-9._]+)\/([A-Za-z0-9_-]+)" . $suffix . "/i",
                    // /stories/highlights/{highlightId}
                    "/^(?:https?:\/\/)?(?:www\.)?instagram\.com\/stories\/highlights\/([0-9]+)" . $suffix . "/i",
                ];

            default:
                return [];
        }
    }
}
