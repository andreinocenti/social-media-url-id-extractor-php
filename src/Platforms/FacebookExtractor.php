<?php

namespace AndreInocenti\SocialMediaUrlIdExtractor\Platforms;

use AndreInocenti\SocialMediaUrlIdExtractor\Contracts\PlatformExtractorInterface;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

class FacebookExtractor extends AbstractExtractor implements PlatformExtractorInterface
{
    protected function getPatterns(PlatformsCategoriesEnum $resourceType): array
    {
        // domain variations: facebook.com, fb.com, m.facebook.com, fb.watch
        $suffix = '/?(?:\?.*)?$';
        switch ($resourceType) {
            case PlatformsCategoriesEnum::USER:
            case PlatformsCategoriesEnum::PROFILE:
            case PlatformsCategoriesEnum::PAGE:
                // ONLY vanity names (dot‑separated or alphanumeric)
                return [
                    // matches e.g. /profile.php?id=123456789  (with optional trailing slash or query)
                    '~^(?:https?://)?(?:www\.|m\.)?facebook\.com/profile\.php\?id=(\d+)(?:/|\b)(?:\?.*)?$~i',
                    // matches e.g. /Example.Page or /username123 (with trailing slash or query)
                    '~^(?:https?://)?(?:www\.|m\.)?facebook\.com/([0-9A-Za-z\.]+)(?:/|\b)(?:\?.*)?$~i',
                ];
            case PlatformsCategoriesEnum::POST:
                return [
                    // /pagename/posts/12345
                    '~^(?:https?://)?(?:www\.|m\.)?facebook\.com/[^/]+/posts/(\d+)(?:/|\b)(?:\?.*)?$~i',
                    // story.php?story_fbid=…
                    '~^(?:https?://)?(?:www\.|m\.)?facebook\.com/story\.php\?story_fbid=(\d+)&id=\d+(?:/|\b)(?:\?.*)?$~i',
                ];
            case PlatformsCategoriesEnum::VIDEO:
                return [
                    // video.php?v=ID or /page/videos/ID
                    '~^(?:https?://)?(?:www\.|m\.)?facebook\.com/(?:video\.php\?v=|[^/]+/videos/)(\d+)(?:/|\b)(?:\?.*)?$~i',
                    // fb.watch short link
                    '~^(?:https?://)?fb\.watch/([A-Za-z0-9_]+)(?:/|\b)(?:\?.*)?$~i',
                ];
            default:
                return [];
        }
    }
}