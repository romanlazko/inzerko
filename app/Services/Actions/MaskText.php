<?php

namespace App\Services\Actions;

use Illuminate\Support\Facades\Pipeline;

class MaskText
{
    public static function maskContactInfo($text)
    {
        return Pipeline::send($text)
            ->through([
                function ($text, $next) {
                    preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $matches);

                    foreach ($matches[0] as $email) {
                        $email = trim($email);

                        $maskedEmail = preg_replace('/[^@.]/', '*', $email);
                        $text = str_replace($email, "******", $text);
                    }

                    return $next($text);
                },
                function ($text, $next) {
                    $words = explode(' ', $text);

                    foreach ($words as $word) {
                        $word = trim($word);

                        if (filter_var($word, FILTER_VALIDATE_EMAIL)) {
                            $maskedEmail = preg_replace('/[^@.]/', '*', $word);
                            $text = str_replace($word, "******", $text);
                        }
                    }

                    return $next($text);
                },
                function ($text, $next) {
                    $phonePattern = '/(?:(?:\+?\d{1,4})?(?:\+?\(?\+?\d{1,4})?[\s\-.]?)?(?:\(?\d{2,4}\)?[\s\-.]?)?\d{2,4}[\s\-.]?\d{2,4}[\s\-.]?\d{2,4}/u';
                    $datePattern = '/\b(?:\d{1,2}[-.\/]\d{1,2}[-.\/]\d{2,4}|\d{4}[-.\/]\d{1,2}[-.\/]\d{1,2})\b/u';

                    preg_match_all($datePattern, $text, $dateMatches);
                    preg_match_all($phonePattern, $text, $phoneMatches);

                    foreach ($phoneMatches[0] as $phone) {
                        $phone = trim($phone);

                        if (!in_array($phone, $dateMatches[0], true)) {
                            $maskedPhone = str_repeat('*', strlen($phone));
                            $text = str_replace($phone, "******", $text);
                        }
                    }

                    return $next($text);
                },
                function ($text, $next) {
                    $words = explode(' ', $text);

                    foreach ($words as $word) {
                        $word = trim($word);

                        if (filter_var($word, FILTER_VALIDATE_URL)) {
                            $maskedUrl = str_repeat('*', strlen($word));
                            $text = str_replace($word, "******", $text);
                        }
                    }

                    return $next($text);
                },
                function ($text, $next) {
                    $words = explode(' ', $text);

                    foreach ($words as $word) {
                        $word = trim($word);
                        
                        $parsedUrl = parse_url($word);
                        if (isset($parsedUrl['scheme']) && isset($parsedUrl['host'])) {
                            $maskedUrl = str_repeat('*', strlen($word));
                            $text = str_replace($word, "******", $text);
                        }
                    }

                    return $next($text);
                },
                function ($text, $next) {
                    preg_match_all('/\b((https?:\/\/|ftp:\/\/|www\.)[a-zA-Z0-9\-.]+(\.[a-zA-Z]{2,})(\/[^\s]*)?)/i', $text, $matches);

                    foreach ($matches[0] as $link) {
                        $link = trim($link);

                        $maskedLink = str_repeat('*', strlen($link));
                        $text = str_replace($link, "******", $text);
                    }

                    return $next($text);
                },
                function ($text, $next) {
                    preg_match_all('/@\b[a-zA-Z0-9_]{5,32}\b/u', $text, $matches);

                    foreach ($matches[0] as $username) {
                        $username = trim($username);

                        $maskedUsername = str_repeat('*', strlen($username));
                        $text = str_replace($username, "******", $text);
                    }

                    return $next($text);
                },
            ])
            ->then(fn ($text) => $text);
    }
}