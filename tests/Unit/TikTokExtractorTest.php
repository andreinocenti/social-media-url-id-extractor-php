<?php

use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\TikTokExtractor;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

beforeEach(function () {
    $this->extractor = new TikTokExtractor();
});

it('extracts TikTok username from main domain', function () {
    $url = 'https://www.tiktok.com/@tiktoker';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::USER))->toBe('tiktoker');
});

it('extracts TikTok username without www', function () {
    $url = 'https://tiktok.com/@user.name';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('user.name');
});

it('extracts TikTok username without www slash', function () {
    $url = 'https://tiktok.com/@user.name/';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('user.name');
});

it('extracts TikTok username without www slash and qs', function () {
    $url = 'https://tiktok.com/@user.name/?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('user.name');
});

it('extracts TikTok username without www qs', function () {
    $url = 'https://tiktok.com/@user.name?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('user.name');
});

it('extracts video ID from vm.tiktok.com', function () {
    $url = 'https://vm.tiktok.com/AbCdEf123/';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('AbCdEf123');
});

it('extracts TikTok video ID from url type 1', function () {
    $url = 'https://www.tiktok.com/@testdeusername/video/7525852090917195014';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('7525852090917195014');
});

it('extracts TikTok video ID from url type slash and querystring', function () {
    $url = 'https://www.tiktok.com/@testdeusername/video/7525852090917195014/?test=test1';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('7525852090917195014');
});

it('extracts TikTok video ID from url querystring', function () {
    $url = 'https://www.tiktok.com/@testdeusername/video/7525852090917195014?test=test1';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('7525852090917195014');
});