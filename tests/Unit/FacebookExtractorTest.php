<?php

use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\FacebookExtractor;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

beforeEach(function () {
    $this->extractor = new FacebookExtractor();
});

it('extracts profile ID from vanity URL', function () {
    $url = 'https://www.facebook.com/Example.Page';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('Example.Page');
});

it('extracts profile ID from vanity URL and slash', function () {
    $url = 'https://www.facebook.com/Example.Page/';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('Example.Page');
});

it('extracts profile ID from vanity URL and slash QS', function () {
    $url = 'https://www.facebook.com/Example.Page/?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('Example.Page');
});

it('extracts numeric profile ID from profile.php URL', function () {
    $url = 'https://facebook.com/profile.php?id=123456789';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::PROFILE))->toBe('123456789');
});

it('extracts post ID from /posts/', function () {
    $url = 'https://m.facebook.com/ExamplePage/posts/987654321';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('987654321');
});

it('extracts post ID from /posts/ and slash', function () {
    $url = 'https://m.facebook.com/ExamplePage/posts/987654321/';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('987654321');
});

it('extracts post ID from /posts/ qs', function () {
    $url = 'https://m.facebook.com/ExamplePage/posts/987654321?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('987654321');
});

it('extracts post ID from /posts/ slash and qs', function () {
    $url = 'https://m.facebook.com/ExamplePage/posts/987654321/?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('987654321');
});

it('extracts story post ID', function () {
    $url = 'https://facebook.com/story.php?story_fbid=555666777&id=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::POST))->toBe('555666777');
});

it('extracts video ID from fb.watch short link', function () {
    $url = 'https://fb.watch/abcDEF123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('abcDEF123');
});

it('extracts video ID from fb.watch short link slash', function () {
    $url = 'https://fb.watch/abcDEF123/';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('abcDEF123');
});


it('extracts video ID from fb.watch short link qs', function () {
    $url = 'https://fb.watch/abcDEF123?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('abcDEF123');
});


it('extracts video ID from fb.watch short link qs and slash', function () {
    $url = 'https://fb.watch/abcDEF123/?test=123';
    expect($this->extractor->extractId($url, PlatformsCategoriesEnum::VIDEO))->toBe('abcDEF123');
});
