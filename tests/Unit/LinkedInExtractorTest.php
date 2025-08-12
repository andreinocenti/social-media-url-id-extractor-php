<?php

use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\LinkedInExtractor;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum as Cat;

beforeEach(function () {
    $this->extractor = new LinkedInExtractor();
});

//
// DATASETS
//

// /in/{slug}
dataset('li-profiles-in', [
    'basic'            => ['https://linkedin.com/in/public-user-123',                     'public-user-123'],
    'www + slash'      => ['https://www.linkedin.com/in/public-user-123/',                'public-user-123'],
    'with query'       => ['https://linkedin.com/in/public-user-123?test=123&asda=234',   'public-user-123'],
    'http scheme'      => ['http://linkedin.com/in/public-user-123',                      'public-user-123'],
    "complex slug"    => ['https://www.linkedin.com/in/kakaka-asdadj%C3%B3-8302652b/',  'kakaka-asdadj%C3%B3-8302652b'],
]);

// legacy /pub/{slug}
dataset('li-profiles-pub', [
    'pub basic'        => ['https://www.linkedin.com/pub/john-doe-1b2c3d',                'john-doe-1b2c3d'],
    'pub with slash'   => ['https://linkedin.com/pub/john-doe-1b2c3d/',                   'john-doe-1b2c3d'],
    'pub with query'   => ['https://linkedin.com/pub/john-doe-1b2c3d?trk=xyz',            'john-doe-1b2c3d'],
]);

// lnkd.in short
dataset('li-short', [
    'short basic'      => ['https://lnkd.in/xyzABC',                                      'xyzABC'],
    'short slash'      => ['https://lnkd.in/xyzABC/',                                     'xyzABC'],
    'short query'      => ['https://lnkd.in/xyzABC?utm_source=share',                     'xyzABC'],
]);

// company
dataset('li-company', [
    'company basic'    => ['https://linkedin.com/company/examplecorp/',                   'examplecorp'],
    'company no www'   => ['https://linkedin.com/company/examplecorp',                    'examplecorp'],
    'company query'    => ['https://www.linkedin.com/company/examplecorp?trk=public',     'examplecorp'],
]);

// activity (feed/update/urn:li:activity:<id>)
dataset('li-activity', [
    'activity basic'   => ['https://www.linkedin.com/feed/update/urn:li:activity:1234567890123456789',   '1234567890123456789'],
    'activity slash'   => ['https://www.linkedin.com/feed/update/urn:li:activity:1234567890123456789/',  '1234567890123456789'],
    'activity query'   => ['https://www.linkedin.com/feed/update/urn:li:activity:1234567890123456789/?test=123', '1234567890123456789'],
]);

// posts ( /posts/{username}_{slug} ) → extrair **apenas** o trecho após o 1º "_"
dataset('li-posts', [
    'short slug'       => ['https://linkedin.com/posts/example-user-123_postIdXYZ', 'postIdXYZ'],
    'long pt-encoded'  => ['https://www.linkedin.com/posts/jnjinnovativemedicinebrasil_avan%C3%A7os-no-tratamento-do-mieloma-multiplo-activity-7349525945143197697-5YSU/?utm_source=social_share_send&utm_medium=member_desktop_web&rcm=ACoAAARdaMQBlO6dVjr8kIujY9JewzbT6sLqEdE', 'avan%C3%A7os-no-tratamento-do-mieloma-multiplo-activity-7349525945143197697-5YSU'],
    'long pt-encoded 2' => ['https://www.linkedin.com/posts/jnjinnovativemedicinebrasil_avan%C3%A7os-no-tratamento-do-mieloma-m%C3%BAltiplo-activity-7349525945143197697-5YSU/?utm_source=social_share_send&utm_medium=member_desktop_web&rcm=ACoAAARdaMQBlO6dVjr8kIujY9JewzbT6sLqEdE', 'avan%C3%A7os-no-tratamento-do-mieloma-m%C3%BAltiplo-activity-7349525945143197697-5YSU'],
    'with trailing'    => ['https://linkedin.com/posts/example-user-123_postIdXYZ/', 'postIdXYZ'],
    'with query'       => ['https://linkedin.com/posts/example-user-123_postIdXYZ?trk=share', 'postIdXYZ'],
]);

// Negativos — tipo errado ou URL incompleta → deve lançar InvalidArgumentException
dataset('li-negatives', [
    // tipos trocados
    ['https://linkedin.com/in/public-user-123',                               Cat::COMPANY],   // é PROFILE, não COMPANY
    ['https://linkedin.com/company/examplecorp',                              Cat::PROFILE],   // é COMPANY, não PROFILE
    ['https://www.linkedin.com/feed/update/urn:li:activity:1234567890',       Cat::PROFILE],   // é ACTIVITY, não PROFILE
    ['https://linkedin.com/posts/example-user-123_postIdXYZ',                 Cat::PROFILE],   // é ACTIVITY, não PROFILE
    // incompletas
    ['https://www.linkedin.com/feed/update/urn:li:activity:',                 Cat::ACTIVITY],
    ['https://www.linkedin.com/posts/example-user-123_',                      Cat::ACTIVITY],
    ['https://lnkd.in/',                                                      Cat::USER],
    // rotas fora do escopo
    ['https://www.linkedin.com/login',                                        Cat::PROFILE],
    ['https://www.linkedin.com/school/some-uni',                              Cat::PROFILE],
]);

describe('LinkedIn', function () {
    //
    // TESTES
    //

    test('extracts LinkedIn PROFILE id from /in/...', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::PROFILE))->toBe($expected);
    })->with('li-profiles-in');

    test('extracts LinkedIn PROFILE id from legacy /pub/...', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::PROFILE))->toBe($expected);
    })->with('li-profiles-pub');

    test('extracts LinkedIn USER id from lnkd.in short links', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::USER))->toBe($expected);
    })->with('li-short');

    test('extracts LinkedIn COMPANY id', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::COMPANY))->toBe($expected);
    })->with('li-company');

    test('extracts LinkedIn ACTIVITY id (feed/update/urn:li:activity:...)', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::ACTIVITY))->toBe($expected);
    })->with('li-activity');

    test('extracts LinkedIn ACTIVITY id from /posts/{username}_{slug}', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::ACTIVITY))->toBe($expected);
    })->with('li-posts');

    //
    // NEGATIVOS — deve lançar InvalidArgumentException
    //
    test('throws on wrong category or malformed LinkedIn URL', function (string $url, Cat $type) {
        $this->expectException(\InvalidArgumentException::class);
        $this->extractor->extractId($url, $type);
    })->with('li-negatives');

    // Smoke: mantém alguns dos seus originais
    it('extracts LinkedIn public profile ID (smoke)', function () {
        $url = 'https://linkedin.com/in/public-user-123';
        expect($this->extractor->extractId($url, Cat::PROFILE))->toBe('public-user-123');
    });

    it('extracts post activity ID (smoke)', function () {
        $url = 'https://www.linkedin.com/feed/update/urn:li:activity:1234567890123456789';
        expect($this->extractor->extractId($url, Cat::ACTIVITY))->toBe('1234567890123456789');
    });
});