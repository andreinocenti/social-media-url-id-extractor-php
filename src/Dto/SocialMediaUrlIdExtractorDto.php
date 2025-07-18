<?php
namespace AndreInocenti\SocialMediaUrlIdExtractor\Dto;

class SocialMediaUrlIdExtractorDto
{
    public function __construct(
        public readonly string $url,
        public readonly ?string $id = null,
        public readonly ?string $provider = null,
        public readonly ?string $resourceType = null,
    )
    {
    }
}
