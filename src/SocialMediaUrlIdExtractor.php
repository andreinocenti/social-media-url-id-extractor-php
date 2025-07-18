<?php

namespace AndreInocenti\SocialMediaUrlIdExtractor;

use AndreInocenti\SocialMediaUrlIdExtractor\Dto\SocialMediaUrlIdExtractorDto;
use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\FacebookExtractor;
use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\InstagramExtractor;
use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\LinkedInExtractor;
use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\TikTokExtractor;
use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\XExtractor;
use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\YouTubeExtractor;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsEnum;
use AndreInocenti\SocialMediaUrlValidator\UrlValidator;

class SocialMediaUrlIdExtractor
{
    /**
     * Extracts the ID from a social media URL.
     *
     * @param string $url The social media URL.
     * @return SocialMediaUrlIdExtractorDto|null The extracted ID or null if not found.
     */
    public function extract(string $url): ?SocialMediaUrlIdExtractorDto
    {
        $validator = new UrlValidator();
        $provider = $validator->detectSocialMedia($url);
        $provider = PlatformsEnum::tryFrom($provider ?: '');

        $resourceType = $validator->detectSocialMediaCategory($url);
        $resourceType = PlatformsCategoriesEnum::tryFrom($resourceType ?: '');

        if (!$provider) {
            throw new \Exception("Provider (Social Media) is not supported ", 1);
        }
        if (!$resourceType) {
            throw new \Exception("Social Media resource type is not supported ", 1);
        }


        $id = match($provider) {
            PlatformsEnum::INSTAGRAM => (new InstagramExtractor())->extractId($url, $resourceType),
            PlatformsEnum::FACEBOOK => (new FacebookExtractor())->extractId($url, $resourceType),
            PlatformsEnum::YOUTUBE => (new YouTubeExtractor())->extractId($url, $resourceType),
            PlatformsEnum::X => (new XExtractor())->extractId($url, $resourceType),
            PlatformsEnum::TIKTOK => (new TikTokExtractor())->extractId($url, $resourceType),
            PlatformsEnum::LINKEDIN => (new LinkedInExtractor())->extractId($url, $resourceType),
            default => throw new \Exception("Provider (Social Media) is not supported ", 1),
        };

        return new SocialMediaUrlIdExtractorDto(
            url: $url,
            id: $id,
            provider: $provider->value,
            resourceType: $resourceType->value
        );
    }
}