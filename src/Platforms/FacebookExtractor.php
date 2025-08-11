<?php

namespace AndreInocenti\SocialMediaUrlIdExtractor\Platforms;

use AndreInocenti\SocialMediaUrlIdExtractor\Contracts\PlatformExtractorInterface;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

class FacebookExtractor extends AbstractExtractor implements PlatformExtractorInterface
{
    protected function getPatterns(PlatformsCategoriesEnum $resourceType): array
    {
        // Sufixo: barra final opcional + query/fragment opcionais (inclui &, #)
        $suffix = '(?:/)?(?:[?#&].*)?$';
        $sub    = '(?:www\.|m\.|mbasic\.)?';

        switch ($resourceType) {
            case PlatformsCategoriesEnum::USER:
            case PlatformsCategoriesEnum::PROFILE:
            case PlatformsCategoriesEnum::PAGE:
                return [
                    // profile.php?id=123... (+ /, ?, #, & opcionais após o ID)
                    "~^(?:https?://)?{$sub}facebook\\.com/profile\\.php\\?id=(\\d+){$suffix}~i",

                    // Vanity (@page/user) — evita rotas reservadas (posts, videos, watch, reel, groups, story.php, profile.php, etc.)
                    "~^(?:https?://)?{$sub}facebook\\.com/"
                        . "(?!(?:posts|videos|watch|reel|groups|story\\.php|events|help|gaming|marketplace|messages|notifications|settings|home|plugins|privacy|policies|legal|people|pages|places|permalink|profile\\.php)"
                        . "(?:/|$|[?#&]))"
                        . "([0-9A-Za-z\\.]+){$suffix}~i",
                ];

            case PlatformsCategoriesEnum::POST:
                return [
                    // /{page}/posts/{id|pfbid...}
                    "~^(?:https?://)?{$sub}facebook\\.com/[^/]+/posts/([A-Za-z0-9_-]+){$suffix}~i",

                    // story.php?story_fbid=...&id=...
                    "~^(?:https?://)?{$sub}facebook\\.com/story\\.php\\?story_fbid=(\\d+)&id=\\d+{$suffix}~i",

                    // groups/{gid}/(posts|permalink)/{pid}
                    "~^(?:https?://)?{$sub}facebook\\.com/groups/\\d+/(?:posts|permalink)/([A-Za-z0-9]+){$suffix}~i",
                ];

            case PlatformsCategoriesEnum::VIDEO:
                return [
                    // video.php?v={id}
                    "~^(?:https?://)?{$sub}facebook\\.com/video\\.php\\?v=(\\d+){$suffix}~i",

                    // /{page}/videos/{id}
                    "~^(?:https?://)?{$sub}facebook\\.com/[^/]+/videos/(\\d+){$suffix}~i",

                    // watch (? com ou sem barra) ?v={id}
                    "~^(?:https?://)?{$sub}facebook\\.com/watch(?:/)?\\?v=([A-Za-z0-9]+){$suffix}~i",

                    // fb.watch/{code}
                    "~^(?:https?://)?fb\\.watch/([A-Za-z0-9_]+){$suffix}~i",

                    // reel/{id}
                    "~^(?:https?://)?{$sub}facebook\\.com/reel/([A-Za-z0-9]+){$suffix}~i",
                ];

            default:
                return [];
        }
    }
}
