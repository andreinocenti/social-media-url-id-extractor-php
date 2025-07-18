<?php

use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\LinkedInExtractor;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;


beforeEach(function () {
    $this->extractor = new LinkedInExtractor();
});

it('extracts LinkedIn public profile ID', function () {
    $url = 'https://linkedin.com/in/public-user-123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('public-user-123');
});

it('extracts LinkedIn public profile ID slash', function () {
    $url = 'https://linkedin.com/in/public-user-123/';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('public-user-123');
});

it('extracts LinkedIn public profile ID slash and qs', function () {
    $url = 'https://linkedin.com/in/public-user-123/?test=123&asda=234';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('public-user-123');
});

it('extracts LinkedIn public profile ID and qs', function () {
    $url = 'https://linkedin.com/in/public-user-123?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('public-user-123');
});

it('extracts LinkedIn short URL ID', function () {
    $url = 'https://lnkd.in/xyzABC';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::USER))->toBe('xyzABC');
});

it('extracts company public ID', function () {
    $url = 'https://linkedin.com/company/examplecorp/';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::COMPANY))->toBe('examplecorp');
});

it('extracts post activity ID', function () {
    $url = 'https://www.linkedin.com/feed/update/urn:li:activity:1234567890123456789';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::ACTIVITY))->toBe('1234567890123456789');
});

it('extracts post activity ID slash', function () {
    $url = 'https://www.linkedin.com/feed/update/urn:li:activity:1234567890123456789/';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::ACTIVITY))->toBe('1234567890123456789');
});

it('extracts post activity ID slash and qs', function () {
    $url = 'https://www.linkedin.com/feed/update/urn:li:activity:1234567890123456789/?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::ACTIVITY))->toBe('1234567890123456789');
});

it('extracts post ID from /posts/', function () {
    $url = 'https://linkedin.com/posts/example-user-123_postIdXYZ';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::ACTIVITY))->toBe('postIdXYZ');
});

it('extracts post ID from long posts', function () {
    $url = 'https://www.linkedin.com/posts/jnjinnovativemedicinebrasil_avan%C3%A7os-no-tratamento-do-mieloma-multiplo-activity-7349525945143197697-5YSU/?utm_source=social_share_send&utm_medium=member_desktop_web&rcm=ACoAAARdaMQBlO6dVjr8kIujY9JewzbT6sLqEdE';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::ACTIVITY))->toBe('avan%C3%A7os-no-tratamento-do-mieloma-multiplo-activity-7349525945143197697-5YSU');
});

it('extracts post ID from long posts2', function () {
    $url = 'https://www.linkedin.com/posts/jnjinnovativemedicinebrasil_avan%C3%A7os-no-tratamento-do-mieloma-m%C3%BAltiplo-activity-7349525945143197697-5YSU/?utm_source=social_share_send&utm_medium=member_desktop_web&rcm=ACoAAARdaMQBlO6dVjr8kIujY9JewzbT6sLqEdE';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::ACTIVITY))->toBe('avan%C3%A7os-no-tratamento-do-mieloma-m%C3%BAltiplo-activity-7349525945143197697-5YSU');
});

