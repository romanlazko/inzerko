<?php

namespace App\Jobs;

use App\Models\Announcement;
use App\Services\Actions\MaskText;
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
        if ($this->announcement->status->isAwaitMaskingContacts()) {
            foreach ($this->announcement->features as $feature) {
                if (is_string($feature->original)) {
                    $text = MaskText::maskContactInfo($feature->original);

                    $feature->update([
                        'translated_value' => [
                            'original' => $text,
                        ]
                    ]);
                }
            }

            $this->announcement->maskedContacts([
                'message' => 'Contacts masked'
            ]);
        }
    }

    public function failed(Throwable $exception): void
    {
        $this->announcement->maskingContactsFailed(['info' => $exception->getMessage()]);
    }
}