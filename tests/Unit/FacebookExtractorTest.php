<?php

use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\FacebookExtractor;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum as Cat;

beforeEach(function () {
    $this->extractor = new FacebookExtractor();
});

//
// DATASETS
//

dataset('fb-profile-vanity', [
    'plain'              => ['https://www.facebook.com/Example.Page',                      'Example.Page'],
    'trailing slash'     => ['https://www.facebook.com/Example.Page/',                     'Example.Page'],
    'with query'         => ['https://www.facebook.com/Example.Page/?test=123',            'Example.Page'],
    'subdomain m'        => ['https://m.facebook.com/Example.Page',                        'Example.Page'],
    'subdomain mbasic'   => ['https://mbasic.facebook.com/Example.Page?ref=bookmarks',     'Example.Page'],
    'dots and caps'      => ['https://www.facebook.com/Ex.Ample.Page/',                    'Ex.Ample.Page'],
]);

dataset('fb-profile-numeric', [
    'numeric basic'      => ['https://facebook.com/profile.php?id=123456789',              '123456789'],
    'numeric with query' => ['https://www.facebook.com/profile.php?id=123456789&ref=bm',   '123456789'],
    'numeric m'          => ['https://m.facebook.com/profile.php?id=123456789/',           '123456789'],
]);

dataset('fb-posts', [
    // page/user posts (numeric)
    'page posts basic'   => ['https://m.facebook.com/ExamplePage/posts/987654321',         '987654321'],
    'page posts slash'   => ['https://m.facebook.com/ExamplePage/posts/987654321/',        '987654321'],
    'page posts query'   => ['https://www.facebook.com/ExamplePage/posts/987654321?x=1',   '987654321'],
    // page/user posts (pfbid)
    'pfbid post'         => ['https://www.facebook.com/REVISTABOOKINGR/posts/pfbid0AzwwUU8puAowPPuwxg6RqSbj44kFTT3STZsFwJof6DbBbRis79s6kxes13J5HYnWl', 'pfbid0AzwwUU8puAowPPuwxg6RqSbj44kFTT3STZsFwJof6DbBbRis79s6kxes13J5HYnWl'],
    // numeric id on numeric page
    'numeric page post'  => ['https://www.facebook.com/491794399620983/posts/1121416323325451', '1121416323325451'],
    // story.php
    'story basic'        => ['https://facebook.com/story.php?story_fbid=555666777&id=123', '555666777'],
    'story with _rdr'    => ['https://www.facebook.com/story.php?story_fbid=1057786096151501&id=100057603602454&_rdr', '1057786096151501'],
    'story slash query'  => ['https://www.facebook.com/story.php?story_fbid=1057786096151501&id=100057603602454/&x=1', '1057786096151501'],
    // groups
    'group posts'        => ['https://www.facebook.com/groups/123456789012345/posts/987654321098765', '987654321098765'],
    'group permalink'    => ['https://www.facebook.com/groups/123456789012345/permalink/11223344556677/?utm=1', '11223344556677'],
    // pfbid with query/fragment
    'pfbid q frag'       => ['https://www.facebook.com/livealok/posts/pfbid02hZiXMYmzApzCTyPtPdFJcjNoLctb4UjjQ4ZWNRmC1jyWBwpGdAEmnpRQYWZgtftrl?rdid=EDuUBbnFKGvqROQx#', 'pfbid02hZiXMYmzApzCTyPtPdFJcjNoLctb4UjjQ4ZWNRmC1jyWBwpGdAEmnpRQYWZgtftrl'],
]);

dataset('fb-videos', [
    // page videos/
    'page videos'        => ['https://www.facebook.com/PageName/videos/1234567890123456/', '1234567890123456'],
    'page videos query'  => ['https://www.facebook.com/PageName/videos/1234567890123456/?ref=share', '1234567890123456'],
    // video.php?v=
    'video.php basic'    => ['https://m.facebook.com/video.php?v=9876543210',              '9876543210'],
    'video.php query'    => ['https://www.facebook.com/video.php?v=9876543210&set=vb.1',   '9876543210'],
    // watch/?v=
    'watch v'            => ['https://www.facebook.com/watch/?v=123456789012345',          '123456789012345'],
    'watch v noslash'    => ['https://www.facebook.com/watch?v=123456789012345',           '123456789012345'],
    // fb.watch
    'fb.watch'           => ['https://fb.watch/abcDEF123',                                 'abcDEF123'],
    'fb.watch slash'     => ['https://fb.watch/abcDEF123/',                                'abcDEF123'],
    'fb.watch query'     => ['https://fb.watch/abcDEF123?test=123',                        'abcDEF123'],
    // reel (tratado como vídeo)
    'reel'               => ['https://www.facebook.com/reel/1234567890123456/',            '1234567890123456'],
]);

dataset('fb-negatives', [
    // URLs que NÃO deveriam extrair para o tipo pedido
    ['https://www.facebook.com/PageName/about',      Cat::POST],   // não é post
    ['https://www.facebook.com/PageName/about',      Cat::VIDEO],  // não é vídeo
    ['https://www.facebook.com/watch/',              Cat::VIDEO],  // sem v=ID
    ['https://www.facebook.com/profile.php?id=',     Cat::PROFILE], // sem ID
    ['https://fb.watch/',                            Cat::VIDEO],  // sem code
]);

describe('Facebook', function () {
    //
    // TESTES DE EXTRAÇÃO
    //

    test('extracts PROFILE id from vanity URLs', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::PROFILE))->toBe($expected);
    })->with('fb-profile-vanity');

    test('extracts PROFILE id from profile.php numeric', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::PROFILE))->toBe($expected);
    })->with('fb-profile-numeric');

    test('extracts POST id from many formats', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::POST))->toBe($expected);
    })->with('fb-posts');

    test('extracts VIDEO id from many formats (videos/, video.php, watch, fb.watch, reel)', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::VIDEO))->toBe($expected);
    })->with('fb-videos');

    //
    // NEGATIVOS — deve lançar \InvalidArgumentException quando o tipo não bate
    //

    test('throws when URL does not match requested category', function (string $url, Cat $type) {
        $this->expectException(\InvalidArgumentException::class);
        $this->extractor->extractId($url, $type);
    })->with('fb-negatives');

    //
    // Mantém seus testes originais como fumaça rápida
    //

    it('extracts numeric profile ID from profile.php URL (smoke)', function () {
        $url = 'https://facebook.com/profile.php?id=123456789';
        expect($this->extractor->extractId($url, Cat::PROFILE))->toBe('123456789');
    });

    it('extracts post ID from /posts/ (smoke)', function () {
        $url = 'https://m.facebook.com/ExamplePage/posts/987654321';
        expect($this->extractor->extractId($url, Cat::POST))->toBe('987654321');
    });

    it('extracts video ID from fb.watch short link (smoke)', function () {
        $url = 'https://fb.watch/abcDEF123/';
        expect($this->extractor->extractId($url, Cat::VIDEO))->toBe('abcDEF123');
    });
});