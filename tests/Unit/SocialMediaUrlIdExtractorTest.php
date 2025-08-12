<?php

use AndreInocenti\SocialMediaUrlIdExtractor\SocialMediaUrlIdExtractor;
use AndreInocenti\SocialMediaUrlIdExtractor\Dto\SocialMediaUrlIdExtractorDto;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsEnum;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

beforeEach(function () {
    $this->service = new SocialMediaUrlIdExtractor();
});

it('valid cases return correct DTO', function () {
    $cases = [
        ['https://www.instagram.com/johndoe/', 'johndoe', PlatformsEnum::INSTAGRAM, PlatformsCategoriesEnum::PROFILE],
        ['https://facebook.com/profile.php?id=12345', '12345', PlatformsEnum::FACEBOOK, PlatformsCategoriesEnum::PROFILE],
        ['https://x.com/user/status/67890', '67890', PlatformsEnum::X, PlatformsCategoriesEnum::POST],
        ['https://linkedin.com/in/test-user', 'test-user', PlatformsEnum::LINKEDIN, PlatformsCategoriesEnum::PROFILE],
        ['https://www.linkedin.com/in/janedoe/', 'janedoe', PlatformsEnum::LINKEDIN, PlatformsCategoriesEnum::PROFILE],
        ['https://www.youtube.com/watch?v=VIDEO123', 'VIDEO123', PlatformsEnum::YOUTUBE, PlatformsCategoriesEnum::VIDEO],
        ['https://youtu.be/VIDEO123', 'VIDEO123', PlatformsEnum::YOUTUBE, PlatformsCategoriesEnum::VIDEO],
        ['https://tiktok.com/@user/video/1234567890', '1234567890', PlatformsEnum::TIKTOK, PlatformsCategoriesEnum::VIDEO],
        ['https://vm.tiktok.com/AbCdEf/', 'AbCdEf', PlatformsEnum::TIKTOK, PlatformsCategoriesEnum::VIDEO],
        ['https://www.youtube.com/@MyClappy', '@MyClappy', PlatformsEnum::YOUTUBE, PlatformsCategoriesEnum::CHANNEL],
        ['https://www.youtube.com/@canaldigplay/', '@canaldigplay', PlatformsEnum::YOUTUBE, PlatformsCategoriesEnum::CHANNEL],
    ];

    foreach ($cases as [$url, $id, $platform, $resource]) {
        $dto = $this->service->extract($url);
        expect($dto)->toBeInstanceOf(SocialMediaUrlIdExtractorDto::class)
            ->and($dto->id)->toBe($id)
            ->and($dto->provider)->toBe($platform->value)
            ->and($dto->resourceType)->toBe($resource->value);
    }
});

it('throws on unsupported provider', function () {
    $this->service->extract('https://unknown.fake/path');
})->throws(Exception::class, 'Provider (Social Media) is not supported');

it('throws on invalid URL format', function () {
    $this->service->extract('not a url');
})->throws(Exception::class);