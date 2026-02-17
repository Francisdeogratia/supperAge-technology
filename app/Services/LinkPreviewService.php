<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LinkPreviewService
{
    public static function fetchPreview($url)
    {
        try {
            // Validate URL
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return null;
            }

            // Fetch the page content
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ])
                ->get($url);

            if (!$response->successful()) {
                return null;
            }

            $html = $response->body();
            
            // Extract metadata
            $preview = [
                'url' => $url,
                'title' => self::extractTitle($html),
                'description' => self::extractDescription($html),
                'image' => self::extractImage($html, $url),
                'site_name' => self::extractSiteName($html, $url),
                'favicon' => self::extractFavicon($html, $url),
            ];

            return $preview;

        } catch (\Exception $e) {
            \Log::error('Link preview error: ' . $e->getMessage());
            return null;
        }
    }

    private static function extractTitle($html)
    {
        // Try Open Graph title
        if (preg_match('/<meta[^>]+property=["\']og:title["\'][^>]+content=["\'](.*?)["\']/i', $html, $match)) {
            return html_entity_decode($match[1], ENT_QUOTES | ENT_HTML5);
        }

        // Try Twitter title
        if (preg_match('/<meta[^>]+name=["\']twitter:title["\'][^>]+content=["\'](.*?)["\']/i', $html, $match)) {
            return html_entity_decode($match[1], ENT_QUOTES | ENT_HTML5);
        }

        // Try regular title tag
        if (preg_match('/<title[^>]*>(.*?)<\/title>/i', $html, $match)) {
            return html_entity_decode(trim($match[1]), ENT_QUOTES | ENT_HTML5);
        }

        return null;
    }

    private static function extractDescription($html)
    {
        // Try Open Graph description
        if (preg_match('/<meta[^>]+property=["\']og:description["\'][^>]+content=["\'](.*?)["\']/i', $html, $match)) {
            return html_entity_decode(Str::limit($match[1], 200), ENT_QUOTES | ENT_HTML5);
        }

        // Try Twitter description
        if (preg_match('/<meta[^>]+name=["\']twitter:description["\'][^>]+content=["\'](.*?)["\']/i', $html, $match)) {
            return html_entity_decode(Str::limit($match[1], 200), ENT_QUOTES | ENT_HTML5);
        }

        // Try meta description
        if (preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\'](.*?)["\']/i', $html, $match)) {
            return html_entity_decode(Str::limit($match[1], 200), ENT_QUOTES | ENT_HTML5);
        }

        return null;
    }

    private static function extractImage($html, $baseUrl)
    {
        // Try Open Graph image
        if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\'](.*?)["\']/i', $html, $match)) {
            return self::normalizeUrl($match[1], $baseUrl);
        }

        // Try Twitter image
        if (preg_match('/<meta[^>]+name=["\']twitter:image["\'][^>]+content=["\'](.*?)["\']/i', $html, $match)) {
            return self::normalizeUrl($match[1], $baseUrl);
        }

        return null;
    }

    private static function extractSiteName($html, $url)
    {
        // Try Open Graph site name
        if (preg_match('/<meta[^>]+property=["\']og:site_name["\'][^>]+content=["\'](.*?)["\']/i', $html, $match)) {
            return html_entity_decode($match[1], ENT_QUOTES | ENT_HTML5);
        }

        // Extract from domain
        $parsed = parse_url($url);
        if (isset($parsed['host'])) {
            return str_replace('www.', '', $parsed['host']);
        }

        return null;
    }

    private static function extractFavicon($html, $baseUrl)
    {
        // Try finding favicon link
        if (preg_match('/<link[^>]+rel=["\'](?:shortcut )?icon["\'][^>]+href=["\'](.*?)["\']/i', $html, $match)) {
            return self::normalizeUrl($match[1], $baseUrl);
        }

        // Default to /favicon.ico
        $parsed = parse_url($baseUrl);
        if (isset($parsed['scheme']) && isset($parsed['host'])) {
            return $parsed['scheme'] . '://' . $parsed['host'] . '/favicon.ico';
        }

        return null;
    }

    private static function normalizeUrl($url, $baseUrl)
    {
        // If already absolute URL, return as is
        if (preg_match('/^https?:\/\//i', $url)) {
            return $url;
        }

        // Parse base URL
        $parsed = parse_url($baseUrl);
        if (!isset($parsed['scheme']) || !isset($parsed['host'])) {
            return $url;
        }

        $base = $parsed['scheme'] . '://' . $parsed['host'];

        // If starts with //, add scheme
        if (substr($url, 0, 2) === '//') {
            return $parsed['scheme'] . ':' . $url;
        }

        // If starts with /, it's absolute path
        if (substr($url, 0, 1) === '/') {
            return $base . $url;
        }

        // Otherwise, it's relative to current path
        $path = isset($parsed['path']) ? dirname($parsed['path']) : '';
        return $base . $path . '/' . $url;
    }

    public static function extractUrls($text)
    {
        // Regex to match URLs
        $pattern = '/\b(?:https?:\/\/|www\.)[^\s<>"\']+/i';
        preg_match_all($pattern, $text, $matches);
        
        $urls = [];
        foreach ($matches[0] as $url) {
            // Add http:// if starts with www.
            if (substr(strtolower($url), 0, 4) === 'www.') {
                $url = 'http://' . $url;
            }
            $urls[] = $url;
        }
        
        return $urls;
    }
}