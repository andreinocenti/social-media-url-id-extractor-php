<?php

use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\YouTubeExtractor;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum as Cat;

beforeEach(function () {
    $this->extractor = new YouTubeExtractor();
});

//
// DATASETS
//

dataset('yt-videos', [
    'watch basic'           => ['https://www.youtube.com/watch?v=VIDEO12345', 'VIDEO12345'],
    'watch many qs'         => ['https://www.youtube.com/watch?v=VIDEO12345&sad=123&osos=456', 'VIDEO12345'],
    'watch slash before ?'  => ['https://www.youtube.com/watch/?v=VIDEO12345', 'VIDEO12345'],
    'watch with t'          => ['https://youtube.com/watch?v=VIDEO12345&t=42s', 'VIDEO12345'],
    'watch with si'         => ['https://youtube.com/watch?v=VIDEO12345&si=abc_def', 'VIDEO12345'],

    'youtu.be basic'        => ['https://youtu.be/SHORT67890', 'SHORT67890'],
    'youtu.be slash'        => ['https://youtu.be/SHORT67890/', 'SHORT67890'],
    'youtu.be slash + qs'   => ['https://youtu.be/SHORT67890/?test=123', 'SHORT67890'],
    'youtu.be with qs'      => ['https://youtu.be/SHORT67890?test=123', 'SHORT67890'],
    'youtu.be timestamp'    => ['https://youtu.be/SHORT67890?t=43', 'SHORT67890'],

    'shorts basic'          => ['https://youtube.com/shorts/SHORTID123', 'SHORTID123'],
    'shorts with slash'     => ['https://www.youtube.com/shorts/SHORTID123/', 'SHORTID123'],
    'shorts with query'     => ['https://www.youtube.com/shorts/SHORTID123?feature=share', 'SHORTID123'],

    'embed std'             => ['https://www.youtube.com/embed/EMBEDID77', 'EMBEDID77'],
    'embed nocookie'        => ['https://www.youtube-nocookie.com/embed/EMBEDID77', 'EMBEDID77'],
    'embed nocookie qs'     => ['https://www.youtube-nocookie.com/embed/EMBEDID77?rel=0', 'EMBEDID77'],

    // variantes de domínio
    'm.youtube watch'       => ['https://m.youtube.com/watch?v=VIDEO12345', 'VIDEO12345'],
    'music.youtube watch'   => ['https://music.youtube.com/watch?v=VIDEO12345', 'VIDEO12345'], // se seu extractor aceitar music.
]);

dataset('yt-channels', [
    'channel id'            => ['https://www.youtube.com/channel/UCabcdefGHIJKLMN', 'UCabcdefGHIJKLMN'],
    'channel id slash'      => ['https://www.youtube.com/channel/UCabcdefGHIJKLMN/', 'UCabcdefGHIJKLMN'],
    'channel id qs'         => ['https://www.youtube.com/channel/UCabcdefGHIJKLMN?test=123', 'UCabcdefGHIJKLMN'],

    'user legacy'           => ['https://youtube.com/user/legacyUserName', 'legacyUserName'],
    'user legacy slash'     => ['https://www.youtube.com/user/legacyUserName/', 'legacyUserName'],

    'custom /c'             => ['https://www.youtube.com/c/CustomName123', 'CustomName123'],
    'custom /c slash qs'    => ['https://www.youtube.com/c/CustomName123/?foo=bar', 'CustomName123'],

    // @handle — se seu extractor já suporta
    'handle basic'          => ['https://www.youtube.com/@MyClappy', '@MyClappy'],
    'handle with tab'       => ['https://www.youtube.com/@canaldigplay/videos', '@canaldigplay'],

    // root custom — se suportado (ex.: /ancap_su)
    'root custom'           => ['https://www.youtube.com/ancap_su', 'ancap_su'],
    'root custom tab'       => ['https://www.youtube.com/ancap_su/about', 'ancap_su'],
]);

dataset('yt-playlists', [
    'playlist basic'        => ['https://www.youtube.com/playlist?list=PL1234567890', 'PL1234567890'],
    'playlist after watch'  => ['https://www.youtube.com/watch?v=VIDEO12345&list=PL1234567890', 'PL1234567890'],
    'playlist with index'   => ['https://www.youtube.com/playlist?list=PL1234567890&index=2', 'PL1234567890'],
    'playlist many qs'      => ['https://www.youtube.com/playlist?foo=1&list=PL1234567890&bar=2', 'PL1234567890'],
]);

dataset('yt-negatives', [
    // fora do escopo
    ['https://www.youtube.com/results?search_query=php',    Cat::VIDEO],
    ['https://www.youtube.com/feed/trending',               Cat::CHANNEL],

    // faltando ID
    ['https://www.youtube.com/watch?v=',                    Cat::VIDEO],
    ['https://youtu.be/',                                   Cat::VIDEO],
    ['https://www.youtube.com/channel/',                    Cat::CHANNEL],

    // tipo errado
    ['https://www.youtube.com/playlist?list=PL1234567890',  Cat::VIDEO],   // playlist ≠ video
    ['https://www.youtube.com/watch?v=VIDEO12345',          Cat::PLAYLIST], // video ≠ playlist
]);


describe('YouTube', function () {
    //
    // TESTES
    //

    test('extracts VIDEO id from multiple video URL formats', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::VIDEO))->toBe($expected);
    })->with('yt-videos');

    test('extracts CHANNEL/USER/CUSTOM ids from multiple channel URL formats', function (string $url, string $expected) {
        // CHANNEL id
        if (str_contains($url, '/channel/')) {
            expect($this->extractor->extractId($url, Cat::CHANNEL))->toBe($expected);
            return;
        }
        // USER legacy
        if (str_contains($url, '/user/')) {
            expect($this->extractor->extractId($url, Cat::USER))->toBe($expected);
            return;
        }
        // CUSTOM (/c/…), @handle e root custom tratados como CHANNEL
        expect($this->extractor->extractId($url, Cat::CHANNEL))->toBe($expected);
    })->with('yt-channels');

    test('extracts PLAYLIST id from playlist URLs', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::PLAYLIST))->toBe($expected);
    })->with('yt-playlists');

    test('throws when URL is invalid/unsupported or category mismatched', function (string $url, Cat $type) {
        $this->expectException(\InvalidArgumentException::class);
        $this->extractor->extractId($url, $type);
    })->with('yt-negatives');

    //
    // Seus smokes originais (mantidos)
    //

    it('extracts video ID from watch URL (smoke)', function () {
        $url = 'https://www.youtube.com/watch?v=VIDEO12345';
        expect($this->extractor->extractId($url, Cat::VIDEO))->toBe('VIDEO12345');
    });

    it('extracts youtu.be short (smoke)', function () {
        $url = 'https://youtu.be/SHORT67890';
        expect($this->extractor->extractId($url, Cat::VIDEO))->toBe('SHORT67890');
    });

    it('extracts channel ID (smoke)', function () {
        $url = 'https://www.youtube.com/channel/UCabcdefGHIJKLMN';
        expect($this->extractor->extractId($url, Cat::CHANNEL))->toBe('UCabcdefGHIJKLMN');
    });

    it('extracts playlist ID (smoke)', function () {
        $url = 'https://www.youtube.com/playlist?list=PL1234567890';
        expect($this->extractor->extractId($url, Cat::PLAYLIST))->toBe('PL1234567890');
    });
});
