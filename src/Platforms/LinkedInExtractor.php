<?php

namespace AndreInocenti\SocialMediaUrlIdExtractor\Platforms;

use AndreInocenti\SocialMediaUrlIdExtractor\Contracts\PlatformExtractorInterface;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

class LinkedInExtractor extends AbstractExtractor implements PlatformExtractorInterface
{
    protected function getPatterns(PlatformsCategoriesEnum $resourceType): array
    {
        // barra final opcional + query/fragment opcionais
        $suffix = '/?(?:[?#].*)?$';

        switch ($resourceType) {
            case PlatformsCategoriesEnum::USER:
            case PlatformsCategoriesEnum::PROFILE:
                return [
                    // /in/{slug}  — aceita letras unicode, dígitos, _, -, e %XX
                    "~^(?:https?://)?(?:www\\.)?linkedin\\.com/in/([%\\p{L}\\p{N}_-]+)" . $suffix . "~iu",
                    // /pub/{slug} (legado)
                    "~^(?:https?://)?(?:www\\.)?linkedin\\.com/pub/([%\\p{L}\\p{N}_-]+)" . $suffix . "~iu",
                    // lnkd.in encurtado
                    "~^(?:https?://)?lnkd\\.in/([A-Za-z0-9]+)" . $suffix . "~i",
                ];

             case PlatformsCategoriesEnum::COMPANY:
                return [
                    // company OU showcase, com suporte a abas (about|people|posts|jobs|life|updates|insights|events|services)
                    "~^(?:https?://)?(?:[\\w-]+\\.)?linkedin\\.com/(?:company|showcase)/([%\\p{L}\\p{N}_-]+)"
                    . "(?:/(?:about|people|posts|jobs|life|updates|insights|events|services)"
                    . "(?:/[%\\p{L}\\p{N}_-]+)?)?" . $suffix . "~iu",
                ];

            case PlatformsCategoriesEnum::POST:
            case PlatformsCategoriesEnum::ACTIVITY:
                return [
                    // /posts/{username}_{slug} → captura APENAS {slug} até / ? #
                    "~^(?:https?://)?(?:www\\.)?linkedin\\.com/posts/[^/?#]+_([^/?#]+)" . $suffix . "~i",

                    // feed/update/urn:li:activity:{id}
                    "~^(?:https?://)?(?:www\\.)?linkedin\\.com/feed/update/urn:li:activity:(\\d+)" . $suffix . "~i",
                ];

            default:
                return [];
        }
    }
}
