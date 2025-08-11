<?php

use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\XExtractor;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum as Cat;

beforeEach(function () {
    $this->extractor = new XExtractor();
});

//
// DATASETS
//

dataset('x-profiles', [
    'twitter basic'      => ['https://twitter.com/test_user',                   'test_user'],
    'x basic'            => ['https://x.com/anotherUser123',                    'anotherUser123'],
    'x trailing slash'   => ['https://x.com/anotherUser123/',                   'anotherUser123'],
    'x with query'       => ['https://x.com/anotherUser123?test=123',           'anotherUser123'],
    'x slash + query'    => ['https://x.com/anotherUser123/?test=123',          'anotherUser123'],
    'mobile'             => ['https://mobile.twitter.com/some_user',            'some_user'],
    'mobile x'           => ['https://mobile.x.com/some_user',                  'some_user'],
]);

dataset('x-status-canonical', [
    'twitter status'     => ['https://twitter.com/user/status/112233445566',                '112233445566'],
    'x status'           => ['https://x.com/nomeDoUser/status/1946027890505584777',        '1946027890505584777'],
    'mobile twitter'     => ['https://mobile.twitter.com/user/status/112233445566',        '112233445566'],
    'mobile x'           => ['https://mobile.x.com/user/status/112233445566',              '112233445566'],
    'with trailing'      => ['https://twitter.com/user/status/112233445566/',              '112233445566'],
    'with query'         => ['https://twitter.com/user/status/112233445566?src=hash',      '112233445566'],
    'with fragment'      => ['https://x.com/user/status/998877665544#context',             '998877665544'],
    'photo segment'      => ['https://twitter.com/user/status/1234567890123456789/photo/1', '1234567890123456789'],
    'video segment'      => ['https://twitter.com/user/status/1234567890123456789/video/1', '1234567890123456789'],
]);

dataset('x-status-embed', [
    'i web status'       => ['https://twitter.com/i/web/status/998877665544',   '998877665544'],
    'i web status x'     => ['https://x.com/i/web/status/998877665544',         '998877665544'],
    'i web trailing'     => ['https://x.com/i/web/status/998877665544/',        '998877665544'],
    'i web query'        => ['https://x.com/i/web/status/998877665544?test=123', '998877665544'],
    'i web slash+query'  => ['https://x.com/i/web/status/998877665544/?t=1',    '998877665544'],
]);

dataset('x-negatives', [
    // fora do escopo
    ['https://twitter.com/explore',                    Cat::POST],
    ['https://x.com/explore',                          Cat::POST],
    ['https://twitter.com/hashtag/PHP',                Cat::POST],
    ['https://twitter.com/home',                       Cat::PROFILE],
    ['https://x.com/search?q=php',                     Cat::PROFILE],
    ['https://x.com/notifications',                    Cat::PROFILE],

    // incompletas
    ['https://twitter.com/user/status/',               Cat::POST],
    ['https://twitter.com/i/web/status/',              Cat::POST],
    ['https://x.com/',                                  Cat::PROFILE],

    // tipo errado
    ['https://x.com/test_user',                        Cat::POST],   // profile ≠ post
    ['https://twitter.com/user/status/112233',         Cat::PROFILE] // post ≠ profile
]);
describe('X - Twitter', function () {
    //
    // TESTES
    //

    test('extracts PROFILE/USER from profile URLs', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::PROFILE))->toBe($expected);
        expect($this->extractor->extractId($url, Cat::USER))->toBe($expected);
    })->with('x-profiles');

    test('extracts POST id from canonical status URLs (twitter/x, mobile, segments)', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::POST))->toBe($expected);
    })->with('x-status-canonical');

    test('extracts POST id from i/web/status variants', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::POST))->toBe($expected);
    })->with('x-status-embed');

    test('throws on invalid/unsupported or wrong-category X URLs', function (string $url, Cat $type) {
        $this->expectException(\InvalidArgumentException::class);
        $this->extractor->extractId($url, $type);
    })->with('x-negatives');

    //
    // Smokes no seu estilo original (mantidos)
    //

    it('extracts X tweet from x.com (smoke)', function () {
        $url = 'https://x.com/nomeDoUser/status/1946027890505584777';
        expect($this->extractor->extractId($url, Cat::POST))->toBe('1946027890505584777');
    });

    it('extracts tweet ID from web embed URL (smoke)', function () {
        $url = 'https://x.com/i/web/status/998877665544/';
        expect($this->extractor->extractId($url, Cat::POST))->toBe('998877665544');
    });
});