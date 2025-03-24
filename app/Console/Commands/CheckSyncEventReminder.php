<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CheckSyncEventReminder extends Command
{
    protected $signature = 'events:check-sync';
    protected $description = 'Check internet connection and sync cached events if available';

    public function handle()
    {
        try {
            $response = Http::get('https://www.google.com');
            if ($response->successful()) {
                // Check if there are cached events
                $cachedKeys = Cache::get('event_reminder_keys', []);

                if (!empty($cachedKeys)) {
                    // Call the sync route programmatically
                    $syncResponse = Http::get(route('events.sync'));

                    if ($syncResponse->successful()) {
                        $this->info('Events synced successfully.');
                    } else {
                        $this->error('Failed to sync events.');
                    }
                } else {
                    $this->info('No cached events to sync.');
                }
            }
        } catch (\Exception $e) {
            $this->error('No internet connection.');
        }
    }
}
