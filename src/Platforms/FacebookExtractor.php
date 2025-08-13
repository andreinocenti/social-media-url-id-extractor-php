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
        // Aceita qualquer subdomínio: www., m., mbasic., pt-br., web., etc.
        $host   = '(?:[\\w-]+\\.)?facebook\\.com';
        // Slug: letras/dígitos unicode, ., _, -, ou %HH
        $slug   = '(?:%[0-9A-Fa-f]{2}|[\\p{L}\\p{N}._-])+';

        switch ($resourceType) {
            case PlatformsCategoriesEnum::USER:
            case PlatformsCategoriesEnum::PROFILE:
            case PlatformsCategoriesEnum::PAGE:
                return [
                    // profile.php?id=123
                    "~^(?:https?://)?{$host}/profile\\.php\\?id=(\\d+){$suffix}~iu",

                    // profile.php sem id (mantém compat com teu dataset que espera "profile.php")
                    "~^(?:https?://)?{$host}/(profile\\.php){$suffix}~iu",

                    // people/{Nome}/{ID}  -> ID
                    "~^(?:https?://)?{$host}/people/{$slug}/(\\d+){$suffix}~iu",

                    // pages/{Nome}/{ID}  -> ID
                    "~^(?:https?://)?{$host}/pages/{$slug}/(\\d+){$suffix}~iu",
                    // pages/{Nome}       -> Nome
                    "~^(?:https?://)?{$host}/pages/({$slug}){$suffix}~iu",

                    // pages/category/{QualquerCoisa}/{slug}/(posts/)? -> slug
                    "~^(?:https?://)?{$host}/pages/category/[^/]+/({$slug})(?:/posts/?)?{$suffix}~iu",

                    // /pg/{slug} -> slug (versão mobile/legacy de Pages)
                    "~^(?:https?://)?{$host}/pg/({$slug}){$suffix}~iu",

                    // /groups/{id|vanity} -> id ou vanity (teu dataset usa ambos)
                    "~^(?:https?://)?{$host}/groups/({$slug}){$suffix}~iu",

                    // /login/{slug} e /gaming/{slug} -> slug
                    "~^(?:https?://)?{$host}/(?:login|gaming)/({$slug}){$suffix}~iu",

                    // Caso específico: /{algo}/instagram -> "instagram"
                    "~^(?:https?://)?{$host}/[^/]+/(instagram){$suffix}~iu",

                    // Vanity raiz (com subpáginas simples tipo /about, /posts, /photos, /videos…)
                    "~^(?:https?://)?{$host}/"
                        . "(?!(?:posts|videos|watch|reel|groups|story\\.php|events|help|gaming|marketplace|messages|notifications|settings|home|plugins|privacy|policies|legal|people|pages|places|permalink|profile\\.php)"
                        . "(?:/|$|[?#&]))"
                        . "({$slug})"
                        . "(?:/(?:about|posts|photos|videos|events|services|community|reviews|notes)(?:/)?(?:[?#&].*)?)?{$suffix}~iu",
                ];

            case PlatformsCategoriesEnum::POST:
                return [
                    // /{page}/posts/{id|pfbid...}
                    "~^(?:https?://)?{$host}/[^/]+/posts/([A-Za-z0-9_-]+){$suffix}~i",
                    // story.php?story_fbid=...&id=...
                    "~^(?:https?://)?{$host}/story\\.php\\?story_fbid=(\\d+)&id=\\d+{$suffix}~i",
                    // groups/{gid}/(posts|permalink)/{pid}
                    "~^(?:https?://)?{$host}/groups/\\d+/(?:posts|permalink)/([A-Za-z0-9_-]+){$suffix}~i",
                ];

            case PlatformsCategoriesEnum::VIDEO:
                return [
                    // video.php?v={id}
                    "~^(?:https?://)?{$host}/video\\.php\\?v=(\\d+){$suffix}~i",
                    // /{page}/videos/{id}
                    "~^(?:https?://)?{$host}/[^/]+/videos/(\\d+){$suffix}~i",
                    // watch?v={id} (com ou sem / antes do ?)
                    "~^(?:https?://)?{$host}/watch/?\\?v=([A-Za-z0-9]+){$suffix}~i",
                    // fb.watch/{code}
                    "~^(?:https?://)?fb\\.watch/([A-Za-z0-9_]+){$suffix}~i",
                    // reel/{id}
                    "~^(?:https?://)?{$host}/reel/([A-Za-z0-9]+){$suffix}~i",
                ];

            default:
                return [];
        }
    }
}
