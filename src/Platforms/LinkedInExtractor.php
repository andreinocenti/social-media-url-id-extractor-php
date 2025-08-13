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

        // aceita %HH, letras/dígitos unicode, ponto, sublinhado, hífen, apóstrofo e ampersand
        // (precisa do modificador 'u')
        $seg = '((?:%[0-9A-Fa-f]{2}|[\p{L}\p{N}._\'&-])+)';

        // sufixo de idioma opcional em perfis: /pt, /de, /pt-br, etc.
        $localeOpt = '(?:/[a-z]{2}(?:-[a-z]{2})?)?';

        switch ($resourceType) {
            case PlatformsCategoriesEnum::USER:
            case PlatformsCategoriesEnum::PROFILE:
                return [
                    // /in/{slug} [+ opcional /pt, /de, /pt-br...]
                    "~^(?:https?://)?(?:[\\w-]+\\.)?linkedin\\.com/in/{$seg}{$localeOpt}{$suffix}~iu",
                    // /pub/{slug} (legado)
                    "~^(?:https?://)?(?:[\\w-]+\\.)?linkedin\\.com/pub/{$seg}{$localeOpt}{$suffix}~iu",
                    // lnkd.in short
                    "~^(?:https?://)?lnkd\\.in/([A-Za-z0-9]+){$suffix}~i",
                ];

            case PlatformsCategoriesEnum::COMPANY:
                return [
                    // company | showcase | school (todas tratadas como "empresa/página")
                    // aceita abas comuns e também /admin e /admin/updates
                    "~^(?:https?://)?(?:[\\w-]+\\.)?linkedin\\.com/(?:company|showcase|school)/{$seg}"
                        . "(?:/(?:about|people|posts|jobs|life|updates|insights|events|services|admin)"
                        . "(?:/{$seg})?)?{$suffix}~iu",
                ];

            case PlatformsCategoriesEnum::GROUP:
                return [
                    // /groups/{id}/  — aceita qualquer subdomínio (www, br, pt…), barra/qs/fragmento opcionais
                    "~^(?:https?://)?(?:[\\w-]+\\.)?linkedin\\.com/groups/(\\d+){$suffix}~i",
                ];

            case PlatformsCategoriesEnum::POST:
            case PlatformsCategoriesEnum::ACTIVITY:
                return [
                    // /posts/{username}_{slug} → captura apenas {slug}
                    "~^(?:https?://)?(?:[\\w-]+\\.)?linkedin\\.com/posts/[^/?#]+_([^/?#]+){$suffix}~i",

                    // feed/update/urn:li:activity:{id}
                    "~^(?:https?://)?(?:[\\w-]+\\.)?linkedin\\.com/feed/update/urn:li:activity:(\\d+){$suffix}~i",
                ];

            default:
                return [];
        }
    }
}