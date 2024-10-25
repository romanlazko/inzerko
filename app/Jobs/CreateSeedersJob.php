<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class CreateSeedersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private array $seeders)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Artisan::call('iseed', [
            'tables' => implode(',', $this->seeders),
            '--force' => true
        ]);
    }
}
