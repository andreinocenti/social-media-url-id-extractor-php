<?php

use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\XExtractor;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

beforeEach(function () {
    $this->extractor = new XExtractor();
});

it('extracts Twitter username from twitter.com', function () {
    $url = 'https://twitter.com/test_user';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::USER))->toBe('test_user');
});

it('extracts X username from x.com', function () {
    $url = 'https://x.com/anotherUser123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('anotherUser123');
});

it('extracts X username from x.com slash', function () {
    $url = 'https://x.com/anotherUser123/';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('anotherUser123');
});

it('extracts X username from x.com slash and qs', function () {
    $url = 'https://x.com/anotherUser123/?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('anotherUser123');
});

it('extracts X username from x.com qs', function () {
    $url = 'https://x.com/anotherUser123?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('anotherUser123');
});

it('extracts X tweet from x.com', function () {
    $url = 'https://x.com/nomeDoUser/status/1946027890505584777';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('1946027890505584777');
});


it('extracts tweet ID from status URL', function () {
    $url = 'https://mobile.twitter.com/user/status/112233445566';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('112233445566');
});

it('extracts tweet X ID from status URL', function () {
    $url = 'https://mobile.x.com/user/status/112233445566';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('112233445566');
});

it('extracts tweet ID from web embed URL', function () {
    $url = 'https://twitter.com/i/web/status/998877665544';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('998877665544');
});

it('extracts tweet (X) ID from web embed URL', function () {
    $url = 'https://x.com/i/web/status/998877665544';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('998877665544');
});

it('extracts tweet (X) ID from web embed URL slash', function () {
    $url = 'https://x.com/i/web/status/998877665544/';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('998877665544');
});

it('extracts tweet (X) ID from web embed URL Querystring', function () {
    $url = 'https://x.com/i/web/status/998877665544?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('998877665544');
});

it('extracts tweet (X) ID from web embed URL slash and Querystring', function () {
    $url = 'https://x.com/i/web/status/998877665544/?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('998877665544');
});