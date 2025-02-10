<?php

namespace App\Jobs;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Pipeline;
use Throwable;

class MaskAnnouncementContacts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private Announcement $announcement;
    /**
     * Create a new job instance.
     */
    public function __construct(private int $announcement_id)
    {
        $this->announcement = Announcement::find($announcement_id);
    }

    public function handle(): void
    {
        // if ($this->announcement->status->isAwaitMaskingContacts()) {
            foreach ($this->announcement->features as $feature) {
                if (is_string($feature->original)) {
                    $text = Pipeline::send($feature->original)
                        ->through([
                            function ($text, $next) {
                                preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $matches);

                                foreach ($matches[0] as $email) {
                                    $email = trim($email);

                                    $maskedEmail = preg_replace('/[^@.]/', '*', $email);
                                    $text = str_replace($email, $maskedEmail, $text);
                                }

                                return $next($text);
                            },
                            function ($text, $next) {
                                $words = explode(' ', $text);

                                foreach ($words as $word) {
                                    $word = trim($word);

                                    if (filter_var($word, FILTER_VALIDATE_EMAIL)) {
                                        $maskedEmail = preg_replace('/[^@.]/', '*', $word);
                                        $text = str_replace($word, $maskedEmail, $text);
                                    }
                                }

                                return $next($text);
                            },
                            function ($text, $next) {
                                $phonePattern = '/(?:(?:\+?\d{1,3})?[\s\-.]?)?(?:\(?\d{2,4}\)?[\s\-.]?)?\d{2,4}[\s\-.]?\d{2,4}[\s\-.]?\d{2,4}/u';
                                $datePattern = '/\b(?:\d{1,2}[-.\/]\d{1,2}[-.\/]\d{2,4}|\d{4}[-.\/]\d{1,2}[-.\/]\d{1,2})\b/u';

                                preg_match_all($datePattern, $text, $dateMatches);
                                preg_match_all($phonePattern, $text, $phoneMatches);

                                foreach ($phoneMatches[0] as $phone) {
                                    $phone = trim($phone);

                                    if (!in_array($phone, $dateMatches[0], true)) {
                                        $maskedPhone = str_repeat('*', strlen($phone));
                                        $text = str_replace($phone, $maskedPhone, $text);
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
                                        $text = str_replace($word, $maskedUrl, $text);
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
                                        $text = str_replace($word, $maskedUrl, $text);
                                    }
                                }

                                return $next($text);
                            },
                            function ($text, $next) {
                                preg_match_all('/\b((https?:\/\/|ftp:\/\/|www\.)[a-zA-Z0-9\-.]+(\.[a-zA-Z]{2,})(\/[^\s]*)?)/i', $text, $matches);

                                foreach ($matches[0] as $link) {
                                    $link = trim($link);

                                    $maskedLink = str_repeat('*', strlen($link));
                                    $text = str_replace($link, $maskedLink, $text);
                                }

                                return $next($text);
                            },
                            function ($text, $next) {
                                preg_match_all('/@\b[a-zA-Z0-9_]{5,32}\b/u', $text, $matches);

                                foreach ($matches[0] as $username) {
                                    $username = trim($username);

                                    $maskedUsername = str_repeat('*', strlen($username));
                                    $text = str_replace($username, $maskedUsername, $text);
                                }

                                return $next($text);
                            },
                        ])
                        ->then(fn ($text) => $text);

                    $feature->update([
                        'translated_value' => [
                            'original' => $text,
                        ]
                    ]);
                }
            }
        // }
    }

    // public function failed(Throwable $exception): void
    // {
    //     $this->announcement->masFailed(['info' => $exception->getMessage()]);
    // }
}