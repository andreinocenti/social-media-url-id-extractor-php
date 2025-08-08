<?php

namespace AndreInocenti\SocialMediaUrlIdExtractor\Platforms;

use AndreInocenti\SocialMediaUrlIdExtractor\Contracts\PlatformExtractorInterface;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

class YouTubeExtractor extends AbstractExtractor implements PlatformExtractorInterface
{
    protected function getPatterns(PlatformsCategoriesEnum $resourceType): array
    {
        switch ($resourceType) {
            case PlatformsCategoriesEnum::VIDEO:
                return [
                    // watch (com ou sem "/" antes do ?v=) + qualquer query extra
                    "~^(?:https?://)?(?:www\.|m\.)?youtube\.com/watch/?\?v=([A-Za-z0-9_-]+)(?:[&?].*)?$~i",
                    // youtu.be curto
                    "~^(?:https?://)?youtu\.be/([A-Za-z0-9_-]+)(?:/)?(?:[&?].*)?$~i",
                    // embed (inclui nocookie)
                    "~^(?:https?://)?(?:www\.|m\.)?youtube(?:-nocookie)?\.com/embed/([A-Za-z0-9_-]+)(?:/)?(?:[&?].*)?$~i",
                    // shorts
                    "~^(?:https?://)?(?:www\.|m\.)?youtube(?:-nocookie)?\.com/shorts/([A-Za-z0-9_-]+)(?:/)?(?:[&?].*)?$~i",
                ];

            case PlatformsCategoriesEnum::CHANNEL:
                return [
                    // /channel/UC...
                    "~^(?:https?://)?(?:www\.|m\.)?youtube\.com/channel/([A-Za-z0-9_-]+)(?:/)?(?:[&?].*)?$~i",

                    // /@handle  (+ subpáginas comuns de canal, opcional)
                    "~^(?:https?://)?(?:www\.|m\.)?youtube\.com/@([A-Za-z0-9._-]+)(?:/(?:featured|videos|streams|about|community|playlists))?/?(?:[&?].*)?$~i",

                    // custom root: /nome  (+ subpáginas de canal, evita rotas reservadas e @)
                    "~^(?:https?://)?(?:www\.|m\.)?youtube\.com/"
                        . "(?!(?:watch|playlist|shorts|channel|user|c|feed|results|account|about|premium|upload|signin|redirect|embed|t|@)(?:/|\\?|#|$))"
                        . "([A-Za-z0-9_-]+)"
                        . "(?:/(?:featured|videos|streams|about|community|playlists))?/?(?:[&?].*)?$~i",
                ];

            case PlatformsCategoriesEnum::USER:
                return [
                    "~^(?:https?://)?(?:www\.|m\.)?youtube\.com/user/([A-Za-z0-9_-]+)(?:/)?(?:[&?].*)?$~i",
                ];

            case PlatformsCategoriesEnum::CUSTOM:
                return [
                    "~^(?:https?://)?(?:www\.|m\.)?youtube\.com/c/([A-Za-z0-9_-]+)(?:/)?(?:[&?].*)?$~i",
                ];

            case PlatformsCategoriesEnum::PLAYLIST:
                return [
                    "~[?&]list=([A-Za-z0-9_-]+)(?:[&?].*)?$~i",
                ];

            default:
                return [];
        }
    }
    // protected function getPatterns(PlatformsCategoriesEnum $resourceType): array
    // {
    //     // $suffix = '(?:\/|$)(?:\?.*)?$';
    //     switch ($resourceType) {
    //         case PlatformsCategoriesEnum::VIDEO:
    //             return [
    //                 // watch URLs with or without trailing slash, multiple query params
    //                 "~^(?:https?://)?(?:www\.)?youtube\.com/watch/?\?v=([A-Za-z0-9_-]+)(?:[&?].*)?$~i",
    //                 // youtu.be short URLs
    //                 "~^(?:https?://)?youtu\.be/([A-Za-z0-9_-]+)(?:/)?(?:[&?].*)?$~i",
    //                 // embed URLs
    //                 "~^(?:https?://)?(?:www\.)?youtube(?:-nocookie)?\.com/embed/([A-Za-z0-9_-]+)(?:/)?(?:[&?].*)?$~i",
    //                 // shorts URLs
    //                 "~^(?:https?://)?(?:www\.)?youtube(?:-nocookie)?\.com/shorts/([A-Za-z0-9_-]+)(?:/)?(?:[&?].*)?$~i",
    //             ];

    //         case PlatformsCategoriesEnum::CHANNEL:
    //             return [
    //                 "~^(?:https?://)?(?:www\.)?youtube\.com/channel/([A-Za-z0-9_-]+)(?:/)?(?:[&?].*)?$~i",
    //             ];

    //         case PlatformsCategoriesEnum::USER:
    //             return [
    //                 "~^(?:https?://)?(?:www\.)?youtube\.com/user/([A-Za-z0-9_-]+)(?:/)?(?:[&?].*)?$~i",
    //             ];

    //         case PlatformsCategoriesEnum::CUSTOM:
    //             return [
    //                 "~^(?:https?://)?(?:www\.)?youtube\.com/c/([A-Za-z0-9_-]+)(?:/)?(?:[&?].*)?$~i",
    //             ];

    //         case PlatformsCategoriesEnum::PLAYLIST:
    //             return [
    //                 "~[?&]list=([A-Za-z0-9_-]+)(?:[&?].*)?$~i",
    //             ];

    //         default:
    //             return [];
    //     }
    // }
}