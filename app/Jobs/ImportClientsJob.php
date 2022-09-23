<?php

namespace App\Jobs;

use App\Services\ExternalCrmImport\ExternalServiceManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportClientsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    private $page;
    /**
     * @var string
     */
    private $currentService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $page, string $currentService)
    {
        $this->page = $page;
        $this->currentService = $currentService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = ExternalServiceManager::make($this->currentService);

        foreach ($service->clients($this->page) as $client) {
            DB::table('clients')->updateOrInsert(
                \Illuminate\Support\Arr::only($client, ['platform', 'platform_id']),
                $client
            );
        }
    }
}
