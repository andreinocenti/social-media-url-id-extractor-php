# Social Media URL ID Extractor

**Social Media URL ID Extractor** is a lightweight, intuitive PHP package that automatically detects the provider and resource type from any social media URL and extracts the corresponding identifier (ID). No more writing complex regex or handling each platform manually—just pass the URL and receive back the ID.

## 🚀 Main Features

- **Automatic provider detection:** Identifies platforms like Instagram, Facebook, X (Twitter), YouTube, TikTok, LinkedIn, and more
- **Resource type classification:** Profiles, posts, videos, reels, tweets, playlists, pins, etc.
- **Accurate ID extraction:** Returns only the unique identifier (e.g., `ABC123XYZ`, `1234567890`)
- **Single, simple API:** `$result = (new SocialMediaUrlIdExtractor())->extract(string $url)`
- **Supports shortened URLs and UTM query parameters**
- **Extensible:** Easily add new providers to support additional platforms

## 📦 Installation

```bash
composer require andreinocenti/social-media-url-id-extractor-php

## Basic Usage

```php
use AndreInocenti\SocialMediaUrlIdExtractor\SocialMediaUrlIdExtractor;

// Extract ID from a URL
$result = (new SocialMediaUrlIdExtractor())->extract('https://www.instagram.com/p/ABC123XYZ/');
echo $result->getId(); // Outputs: ABC123XYZ
echo $result->getProvider(); // Outputs: Instagram
echo $result->getResourceType(); // Outputs: post