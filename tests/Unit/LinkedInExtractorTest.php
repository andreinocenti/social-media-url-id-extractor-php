<?php

use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\LinkedInExtractor;
use AndreInocenti\SocialMediaUrlIdExtractor\SocialMediaUrlIdExtractor;
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
    "complex slug"     => ['https://www.linkedin.com/in/kakaka-asdadj%C3%B3-8302652b/',   'kakaka-asdadj%C3%B3-8302652b'],
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
    'about'            => ['https://www.linkedin.com/company/kakaka/about/',               'kakaka'],
    'accent'            => ['https://www.linkedin.com/company/kakaka/', 'kakaka'],
    'showcase'            => ['https://www.linkedin.com/showcase/kakaka/', 'kakaka'],
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

dataset('many-linkedin-urls', [
    ["https://www.linkedin.com/school/startse/", 'startse'],
    ["https://www.linkedin.com/company/revista-h&c/", 'revista-h&c'],
    ["https://www.linkedin.com/company/o-hall-&-spotlight-research/about/", 'o-hall-&-spotlight-research'],
    ["https://br.linkedin.com/in/the-capital-advisor-b08302182", 'the-capital-advisor-b08302182'],
    ["https://br.linkedin.com/in/andre-felicissimo-8033a8", 'andre-felicissimo-8033a8'],
    ["https://br.linkedin.com/in/marjorieteixeira", 'marjorieteixeira'],
    ["https://br.linkedin.com/in/isabella-zakzuk-a3a63513", 'isabella-zakzuk-a3a63513'],
    ["https://br.linkedin.com/in/luishsiqueira", 'luishsiqueira'],
    ["https://br.linkedin.com/in/lauravicentini", 'lauravicentini'],
    ["https://www.linkedin.com/company/77194872/admin/", '77194872'],
    ["https://br.linkedin.com/in/andrea-velame-9564731ab", 'andrea-velame-9564731ab'],
    ["https://br.linkedin.com/in/v%C3%A2nia-goy-197b0891", 'v%C3%A2nia-goy-197b0891'],
    ["https://www.linkedin.com/school/30752/", '30752'],
    ["https://br.linkedin.com/in/ana-claudia-thorpe-87176224", 'ana-claudia-thorpe-87176224'],
    ["https://br.linkedin.com/in/armindoferreira/pt", 'armindoferreira'],
    ["https://br.linkedin.com/in/orlando-tambosi-057a6a1a", 'orlando-tambosi-057a6a1a'],
    ["https://br.linkedin.com/in/paulo-roberto-cardoso-945548125", 'paulo-roberto-cardoso-945548125'],
    ["https://br.linkedin.com/in/marcos-pedlowski-b7b68332", 'marcos-pedlowski-b7b68332'],
    ["https://br.linkedin.com/in/rafael-porcari-17110938", 'rafael-porcari-17110938'],
    ["https://br.linkedin.com/in/gustavo-negreiros-0443a956", 'gustavo-negreiros-0443a956'],
    ["https://br.linkedin.com/in/k%C3%A1tya-elpydio-228b706a", 'k%C3%A1tya-elpydio-228b706a'],
    ["https://www.linkedin.com/company/10257971/admin/", '10257971'],
    ["https://br.linkedin.com/in/coluna-supinando-%F0%9F%A6%85%F0%9F%87%A7%F0%9F%87%B7-6109531a9", 'coluna-supinando-%F0%9F%A6%85%F0%9F%87%A7%F0%9F%87%B7-6109531a9'],
    ["https://br.linkedin.com/in/fala-petrolina-aba89616b", 'fala-petrolina-aba89616b'],
    ["https://www.linkedin.com/school/fm2s/", 'fm2s'],
    ["https://www.linkedin.com/groups/4550987/", '4550987'],
    ["https://www.linkedin.com/groups/1827058/", '1827058'],
    ["https://br.linkedin.com/in/paranashop-comunica%C3%A7%C3%A3o-25562a10a", 'paranashop-comunica%C3%A7%C3%A3o-25562a10a'],
    ["https://br.linkedin.com/in/marianagabellini", 'marianagabellini'],
    ["https://www.linkedin.com/groups/8428248/", '8428248'],
    ["https://www.linkedin.com/groups/4862591/", '4862591'],
    ["https://www.linkedin.com/company/abecip---assoc.-bras-das-ent.-de-cr%CC%A9d.-imob.-e-pou/", 'abecip---assoc.-bras-das-ent.-de-cr%CC%A9d.-imob.-e-pou'],
    ["https://www.linkedin.com/company/catve.com/about/", 'catve.com'],
    ["https://www.linkedin.com/groups/1883143/", '1883143'],
    ["https://www.linkedin.com/groups/4862591/", '4862591'],
    ["https://www.linkedin.com/company/oxarope.com/about/", 'oxarope.com'],
    ["https://www.linkedin.com/company/9303866/admin/", '9303866'],
    ["https://www.linkedin.com/school/unit-br/", 'unit-br'],
    ["https://www.linkedin.com/school/uniforoficial/", 'uniforoficial'],
    ["https://www.linkedin.com/school/ufscar/", 'ufscar'],
    ["https://www.linkedin.com/company/what's-rel-/", 'what\'s-rel-'],
    ["https://www.linkedin.com/groups/3992335/", '3992335'],
    ["https://www.linkedin.com/company/zacks-research-pvt.-ltd/", 'zacks-research-pvt.-ltd'],
    ["https://br.linkedin.com/in/wwwzeduducombr", 'wwwzeduducombr'],
    ["https://pt.linkedin.com/company/abdi.digital", 'abdi.digital'],
    ["https://www.linkedin.com/company/pais&filhos/", 'pais&filhos'],
    ["https://www.linkedin.com/showcase/4815222/admin/updates/", '4815222'],
    ["https://www.linkedin.com/company/abdi.digital/", 'abdi.digital'],
    ["https://www.linkedin.com/school/universidade-de-s-o-paulo", 'universidade-de-s-o-paulo'],
    ["https://br.linkedin.com/in/katia-velo-3727476a/de", 'katia-velo-3727476a'],
    ["https://br.linkedin.com/in/tratamentodeagua", 'tratamentodeagua'],
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

    test('many linkedin URLs', function (string $url, string $expected) {
        $extractor = new SocialMediaUrlIdExtractor();
        expect($extractor->extract($url)->id)->toBe($expected);
    })->with('many-linkedin-urls');
});