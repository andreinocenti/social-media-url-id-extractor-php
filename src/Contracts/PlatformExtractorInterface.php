<?php
namespace AndreInocenti\SocialMediaUrlIdExtractor\Contracts;

use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

interface PlatformExtractorInterface
{
    /**
     * Extracts the ID from a social media URL.
     *
     * @param string $url The social media URL.
     * @param PlatformsCategoriesEnum $resourceType The type of resource to extract.
     * @return string|null The extracted ID or null if not found.
     */
    public function extractId(string $url, PlatformsCategoriesEnum $resourceType): ?string;
}