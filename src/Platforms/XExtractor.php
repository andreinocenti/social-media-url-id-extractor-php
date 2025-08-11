<?php

namespace AndreInocenti\SocialMediaUrlIdExtractor\Platforms;

use AndreInocenti\SocialMediaUrlIdExtractor\Contracts\PlatformExtractorInterface;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

class XExtractor extends AbstractExtractor implements PlatformExtractorInterface
{
    protected function getPatterns(PlatformsCategoriesEnum $resourceType): array
    {
        $suffix = '(?:/)?(?:[?#].*)?$';
        $dom    = '(?:www\\.|mobile\\.)?(?:twitter\\.com|x\\.com)';

        switch ($resourceType) {
            case PlatformsCategoriesEnum::POST:
                return [
                    "~^(?:https?://)?{$dom}/[^/]+/status/(\\d+)(?:/(?:photo|video)/\\d+)?{$suffix}~i",
                    "~^(?:https?://)?{$dom}/i/web/status/(\\d+){$suffix}~i",
                ];

            case PlatformsCategoriesEnum::USER:
            case PlatformsCategoriesEnum::PROFILE:
                return [
                    // 👇 aqui está o ajuste: (?:[/?#]|$) em vez de (?:/|$)
                    "~^(?:https?://)?{$dom}/"
                        . "(?!(?:i|home|explore|notifications|settings|search|hashtag|login|signup|compose|messages|tos|privacy|about|share|download)"
                        . "(?:[/?#]|$))"
                        . "([A-Za-z0-9_]{1,15}){$suffix}~i",
                ];

            default:
                return [];
        }
    }
}
