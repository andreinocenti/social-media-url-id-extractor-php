<?php

use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\InstagramExtractor;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum as Cat;

beforeEach(function () {
    $this->extractor = new InstagramExtractor();
});

//
// DATASETS
//

dataset('ig-profiles', [
    'www + slash'            => ['https://www.instagram.com/johndoe/',               'johndoe'],
    'www + query'            => ['https://www.instagram.com/johndoe?test=123',       'johndoe'],
    'www + slash + query'    => ['https://www.instagram.com/johndoe/?hl=pt-br',      'johndoe'],
    'no www'                 => ['https://instagram.com/jane.doe',                   'jane.doe'],
    'http scheme'            => ['http://instagram.com/john_doe',                    'john_doe'],
    'digits'                 => ['https://instagram.com/user123/',                   'user123'],
]);

dataset('ig-posts', [
    'post'                   => ['https://www.instagram.com/p/ABC123_XY',            'ABC123_XY'],
    'post + slash'           => ['https://www.instagram.com/p/ABC123_XY/',           'ABC123_XY'],
    'post + slash + query'   => ['https://www.instagram.com/p/ABC123_XY/?test=123',  'ABC123_XY'],
    'post + query'           => ['https://www.instagram.com/p/ABC123_XY?test=123',   'ABC123_XY'],
]);

dataset('ig-reels', [
    'reel basic'             => ['https://instagram.com/reel/REEL4567',              'REEL4567'],
    'reel + slash'           => ['https://www.instagram.com/reel/REEL4567/',         'REEL4567'],
    'reel + query'           => ['https://www.instagram.com/reel/REEL4567?x=1',      'REEL4567'],
]);

dataset('ig-igtv', [
    'igtv + query'           => ['https://www.instagram.com/tv/IGTV_7890/?utm=feed', 'IGTV_7890'],
    'igtv basic'             => ['https://instagram.com/tv/IGTV_7890',               'IGTV_7890'],
    'igtv + slash'           => ['https://instagram.com/tv/IGTV_7890/',              'IGTV_7890'],
]);

dataset('ig-stories', [
    // stories de usuário
    'story user'             => ['https://www.instagram.com/stories/john.doe/3133700000000000000/', '3133700000000000000'],
    'story user no slash'    => ['https://instagram.com/stories/john_doe/3133700000000000000',      '3133700000000000000'],
    'story user + query'     => ['https://instagram.com/stories/john_doe/3133700000000000000?src=1', '3133700000000000000'],
    // highlights
    'highlight'              => ['https://www.instagram.com/stories/highlights/17901234567890123/', '17901234567890123'],
    'highlight + query'      => ['https://www.instagram.com/stories/highlights/17901234567890123?hl=pt-br', '17901234567890123'],
]);

dataset('ig-negatives', [
    // URL não corresponde ao resource type pedido OU falta ID
    ['https://www.instagram.com/accounts/login/',     Cat::PROFILE],  // rota reservada, não perfil
    ['https://www.instagram.com/p/',                  Cat::POST],     // faltando ID
    ['https://www.instagram.com/reel/',               Cat::REEL],     // faltando ID
    ['https://www.instagram.com/tv/',                 Cat::IGTV],     // faltando ID
    ['https://www.instagram.com/stories/',            Cat::STORY],    // faltando peças
    // tipo errado
    ['https://www.instagram.com/p/ABC123_XY',         Cat::PROFILE],
    ['https://www.instagram.com/johndoe/',            Cat::POST],
    ['https://www.instagram.com/stories/john/1',      Cat::POST],
]);

describe('Instagram', function () {

    //
    // TESTES — PROFILE/USER
    //

    test('extracts PROFILE username from standard instagram URLs', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::PROFILE))->toBe($expected);
    })->with('ig-profiles');

    test('extracts USER username from standard instagram URLs (alias of profile)', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::USER))->toBe($expected);
    })->with('ig-profiles');

    //
    // TESTES — POST, REEL, IGTV
    //

    test('extracts POST id from /p/… variants', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::POST))->toBe($expected);
    })->with('ig-posts');

    test('extracts REEL id from /reel/… variants', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::REEL))->toBe($expected);
    })->with('ig-reels');

    test('extracts IGTV id from /tv/… variants', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::IGTV))->toBe($expected);
    })->with('ig-igtv');

    //
    // TESTES — STORIES (user e highlights)
    //

    test('extracts STORY id from /stories/{user}/{id}', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::STORY))->toBe($expected);
    })->with([
        'story user'          => ['https://www.instagram.com/stories/john.doe/3133700000000000000/', '3133700000000000000'],
        'story user no slash' => ['https://instagram.com/stories/john_doe/3133700000000000000',      '3133700000000000000'],
        'story user query'    => ['https://instagram.com/stories/john_doe/3133700000000000000?utm=1', '3133700000000000000'],
    ]);

    test('extracts STORY id from highlights', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::STORY))->toBe($expected);
    })->with([
        'highlight'           => ['https://www.instagram.com/stories/highlights/17901234567890123/', '17901234567890123'],
        'highlight query'     => ['https://www.instagram.com/stories/highlights/17901234567890123?hl=pt', '17901234567890123'],
    ]);

    //
    // NEGATIVOS — deve lançar \InvalidArgumentException
    //

    test('throws when URL does not match requested instagram category', function (string $url, Cat $type) {
        $this->expectException(\InvalidArgumentException::class);
        $this->extractor->extractId($url, $type);
    })->with('ig-negatives');

    // Smoke tests no teu estilo original
    it('extracts post ID (smoke)', function () {
        $url = 'https://www.instagram.com/p/ABC123_XY';
        expect($this->extractor->extractId($url, Cat::POST))->toBe('ABC123_XY');
    });

    it('extracts reel ID (smoke)', function () {
        $url = 'https://instagram.com/reel/REEL4567';
        expect($this->extractor->extractId($url, Cat::REEL))->toBe('REEL4567');
    });

    it('extracts IGTV ID (smoke)', function () {
        $url = 'https://www.instagram.com/tv/IGTV_7890/?utm_source=feed';
        expect($this->extractor->extractId($url, Cat::IGTV))->toBe('IGTV_7890');
    });
});
