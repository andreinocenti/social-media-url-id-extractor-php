<?php
namespace AndreInocenti\SocialMediaUrlIdExtractor\Platforms;

use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum;

abstract class AbstractExtractor
{
    /**
     * Extracts the resource ID from the given URL using patterns for the given resource type.
     *
     * @param string $url
     * @param PlatformsCategoriesEnum $resourceType
     * @return string
     * @throws \InvalidArgumentException
     */
    public function extractId(string $url, PlatformsCategoriesEnum $resourceType): string
    {
        $patterns = $this->getPatterns($resourceType);
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        throw new \InvalidArgumentException(
            sprintf("Unable to extract %s ID from URL '%s'", $resourceType->value, $url)
        );
    }

    /**
     * Returns an array of regex patterns keyed by ResourceType for each social network.
     *
     * @param PlatformsCategoriesEnum $resourceType
     * @return string[]
     */
    abstract protected function getPatterns(PlatformsCategoriesEnum $resourceType): array;
}