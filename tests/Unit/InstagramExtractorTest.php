<?php

use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\InstagramExtractor;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

beforeEach(function () {
    $this->extractor = new InstagramExtractor();
});

it('extracts profile username from standard instagram URL slash', function () {
    $url = 'https://www.instagram.com/johndoe/';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::USER))->toBe('johndoe');
});

it('extracts profile username from standard instagram URL qs', function () {
    $url = 'https://www.instagram.com/johndoe?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::USER))->toBe('johndoe');
});

it('extracts profile username from standard instagram URL slash and qs', function () {
    $url = 'https://www.instagram.com/johndoe?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::USER))->toBe('johndoe');
});

it('extracts profile username without www', function () {
    $url = 'https://instagram.com/jane.doe';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('jane.doe');
});

it('extracts post ID', function () {
    $url = 'https://www.instagram.com/p/ABC123_XY';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('ABC123_XY');
});

it('extracts post ID slash', function () {
    $url = 'https://www.instagram.com/p/ABC123_XY/';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('ABC123_XY');
});

it('extracts post ID slash and qs', function () {
    $url = 'https://www.instagram.com/p/ABC123_XY/?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('ABC123_XY');
});

it('extracts post ID qs', function () {
    $url = 'https://www.instagram.com/p/ABC123_XY?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('ABC123_XY');
});

it('extracts reel ID', function () {
    $url = 'https://instagram.com/reel/REEL4567';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::REEL))->toBe('REEL4567');
});

it('extracts IGTV ID', function () {
    $url = 'https://www.instagram.com/tv/IGTV_7890/?utm_source=feed';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::IGTV))->toBe('IGTV_7890');
});
