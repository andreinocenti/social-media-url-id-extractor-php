<?php

use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\YouTubeExtractor;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

beforeEach(function () {
    $this->extractor = new YouTubeExtractor();
});

it('extracts video ID from watch URL', function () {
    $url = 'https://www.youtube.com/watch?v=VIDEO12345';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('VIDEO12345');
});

it('extracts video ID from watch URL many qs', function () {
    $url = 'https://www.youtube.com/watch?v=VIDEO12345&sad=123&osos=456';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('VIDEO12345');
});

it('extracts video ID from youtu.be short URL', function () {
    $url = 'https://youtu.be/SHORT67890';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('SHORT67890');
});

it('extracts video ID from youtu.be short URL slash', function () {
    $url = 'https://youtu.be/SHORT67890/';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('SHORT67890');
});

it('extracts video ID from youtu.be short URL slash and qs', function () {
    $url = 'https://youtu.be/SHORT67890/?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('SHORT67890');
});

it('extracts video ID from youtu.be short URL qs', function () {
    $url = 'https://youtu.be/SHORT67890?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('SHORT67890');
});

it('extracts shorts ID', function () {
    $url = 'https://youtube.com/shorts/SHORTID123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('SHORTID123');
});

it('extracts channel ID', function () {
    $url = 'https://www.youtube.com/channel/UCabcdefGHIJKLMN';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::CHANNEL))->toBe('UCabcdefGHIJKLMN');
});

it('extracts channel ID slash', function () {
    $url = 'https://www.youtube.com/channel/UCabcdefGHIJKLMN/';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::CHANNEL))->toBe('UCabcdefGHIJKLMN');
});

it('extracts channel ID slash qs', function () {
    $url = 'https://www.youtube.com/channel/UCabcdefGHIJKLMN/?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::CHANNEL))->toBe('UCabcdefGHIJKLMN');
});

it('extracts channel ID qs', function () {
    $url = 'https://www.youtube.com/channel/UCabcdefGHIJKLMN?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::CHANNEL))->toBe('UCabcdefGHIJKLMN');
});

it('extracts user ID', function () {
    $url = 'https://youtube.com/user/legacyUserName';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::USER))->toBe('legacyUserName');
});

it('extracts custom channel ID', function () {
    $url = 'https://www.youtube.com/c/CustomName123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::CUSTOM))->toBe('CustomName123');
});

it('extracts playlist ID', function () {
    $url = 'https://www.youtube.com/playlist?list=PL1234567890';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PLAYLIST))->toBe('PL1234567890');
});