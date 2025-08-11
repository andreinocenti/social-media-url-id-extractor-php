<?php

use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\TikTokExtractor;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum as Cat;

beforeEach(function () {
    $this->extractor = new TikTokExtractor();
});

//
// DATASETS
//

// Perfis: /@username (com www/sem, barra final, query)
dataset('tt-profiles', [
    'www basic'            => ['https://www.tiktok.com/@tiktoker',             'tiktoker'],
    'no-www basic'         => ['https://tiktok.com/@user.name',                'user.name'],
    'trailing slash'       => ['https://tiktok.com/@user.name/',               'user.name'],
    'with query'           => ['https://tiktok.com/@user.name?test=123',       'user.name'],
    'slash + query'        => ['https://tiktok.com/@user_name/?utm=1',         'user_name'],
]);

// Vídeos “canônicos”: /@user/video/{id} (com/sem barra, query variada)
dataset('tt-videos-canonical', [
    'plain'                => ['https://www.tiktok.com/@testdeusername/video/7525852090917195014',                '7525852090917195014'],
    'slash'                => ['https://www.tiktok.com/@testdeusername/video/7525852090917195014/',               '7525852090917195014'],
    'query'                => ['https://www.tiktok.com/@testdeusername/video/7525852090917195014?test=test1',     '7525852090917195014'],
    'slash + query'        => ['https://www.tiktok.com/@testdeusername/video/7525852090917195014/?is_copy_url=1', '7525852090917195014'],
]);

// Encurtadores e formatos antigos/embeds
dataset('tt-videos-short', [
    // vm.tiktok.com/AbCdEf/ → código “slug”
    'vm short'             => ['https://vm.tiktok.com/AbCdEf123/',                 'AbCdEf123'],
    'vm short no slash'    => ['https://vm.tiktok.com/AbCdEf123',                  'AbCdEf123'],

    // vt e t (curtos atuais)
    'vt short'             => ['https://vt.tiktok.com/ZMabcdefg/',                 'ZMabcdefg'],
    't short'              => ['https://www.tiktok.com/t/ZMabcdefg/',              'ZMabcdefg'],

    // mobile legacy
    'm legacy v'           => ['https://m.tiktok.com/v/6800111222333444555.html',  '6800111222333444555'],

    // embeds
    'embed v2'             => ['https://www.tiktok.com/embed/v2/7525852090917195014', '7525852090917195014'],
    'embed v2 with qs'     => ['https://www.tiktok.com/embed/v2/7525852090917195014?lang=en', '7525852090917195014'],
]);

// Negativos: URL não condiz com o tipo ou está incompleta
dataset('tt-negatives', [
    // caminhos fora do escopo
    ['https://www.tiktok.com/discover/php',          Cat::VIDEO],
    ['https://www.tiktok.com/tag/opensource',        Cat::VIDEO],
    ['https://www.tiktok.com/login',                 Cat::PROFILE],

    // incompletas
    ['https://www.tiktok.com/@user/video/',          Cat::VIDEO],
    ['https://vm.tiktok.com/',                        Cat::VIDEO],
    ['https://www.tiktok.com/t/',                     Cat::VIDEO],

    // tipo errado
    ['https://www.tiktok.com/@user.name',            Cat::VIDEO],   // perfil ≠ vídeo
    ['https://www.tiktok.com/@user/video/123456',    Cat::PROFILE], // vídeo ≠ perfil
]);

describe('TikTok', function () {
    //
    // TESTES
    //

    // PROFILE/USER (alias)
    test('extracts TikTok username from /@… variants (PROFILE)', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::PROFILE))->toBe($expected);
    })->with('tt-profiles');

    test('extracts TikTok username from /@… variants (USER)', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::USER))->toBe($expected);
    })->with('tt-profiles');

    // VIDEO – /@user/video/{id}
    test('extracts TikTok VIDEO id from canonical /@user/video/{id}', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::VIDEO))->toBe($expected);
    })->with('tt-videos-canonical');

    // VIDEO – curtos (vm|vt|t), mobile legacy e embeds
    test('extracts TikTok VIDEO id from short/mobile/embed variants', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::VIDEO))->toBe($expected);
    })->with('tt-videos-short');

    // NEGATIVOS — deve lançar InvalidArgumentException
    test('throws when TikTok URL does not match the requested category or is incomplete', function (string $url, Cat $type) {
        $this->expectException(\InvalidArgumentException::class);
        $this->extractor->extractId($url, $type);
    })->with('tt-negatives');

    //
    // Seus smokes originais (mantidos)
    //

    it('extracts TikTok username from main domain (smoke)', function () {
        $url = 'https://www.tiktok.com/@tiktoker';
        expect($this->extractor->extractId($url, Cat::USER))->toBe('tiktoker');
    });

    it('extracts TikTok username without www (smoke)', function () {
        $url = 'https://tiktok.com/@user.name';
        expect($this->extractor->extractId($url, Cat::PROFILE))->toBe('user.name');
    });

    it('extracts video ID from vm.tiktok.com (smoke)', function () {
        $url = 'https://vm.tiktok.com/AbCdEf123/';
        expect($this->extractor->extractId($url, Cat::VIDEO))->toBe('AbCdEf123');
    });

    it('extracts TikTok video ID from url type 1 (smoke)', function () {
        $url = 'https://www.tiktok.com/@testdeusername/video/7525852090917195014';
        expect($this->extractor->extractId($url, Cat::VIDEO))->toBe('7525852090917195014');
    });

    it('extracts TikTok video ID from url slash and querystring (smoke)', function () {
        $url = 'https://www.tiktok.com/@testdeusername/video/7525852090917195014/?test=test1';
        expect($this->extractor->extractId($url, Cat::VIDEO))->toBe('7525852090917195014');
    });
});