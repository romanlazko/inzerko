<?php

namespace App\Jobs;

use App\Enums\Status;
use App\Facades\Deepl;
use App\Facades\RapidApiTranslator;
use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class TranslateAnnouncement implements ShouldQueue
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

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->announcement->status->isAwaitTranslation()) {

            foreach ($this->announcement->features->where('attribute.is_translatable', true) as $feature) {
                $feature->update([
                    'translated_value' => [
                        'original' => $feature->translated_value['original'],
                        ...RapidApiTranslator::translateToMultipleLanguages($feature->translated_value['original'])
                    ]
                ]);
            }

            $this->announcement->translated();
        }
    }

    public function failed(Throwable $exception): void
    {
        $this->announcement->translationFailed(['info' => $exception->getMessage()]);
    }
}
