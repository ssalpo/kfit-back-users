<?php

namespace App\Console\Commands;

use App\Jobs\ImportClientsJob;
use App\Jobs\ImportOrdersJob;
use App\Jobs\ImportProductsJob;
use App\Services\ExternalCrmImport\ExternalServiceManager;
use Illuminate\Console\Command;

class ImportFromExternal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kfit:import:crm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports data from external crm systems';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $servicePaginationCounts = [
            'autoweboffice' => ['products' => 5, 'clients' => 110, 'orders' => 270],
            'gurucan' => ['products' => 2, 'clients' => 1080, 'orders' => 1080]
        ];

        foreach (ExternalServiceManager::SERVICE_LIST as $service) {
            for ($i = 1; $i <= $servicePaginationCounts[$service]['products']; $i++) {
                ImportProductsJob::dispatch($i, $service)
                    ->onQueue('crmimport')
                    ->delay(now()->addSeconds(5));
            }

            for ($i = 1; $i <= $servicePaginationCounts[$service]['clients']; $i++) {
                ImportClientsJob::dispatch($i, $service)
                    ->onQueue('crmimport')
                    ->delay(now()->addSeconds(5));
            }

            for ($i = 1; $i <= $servicePaginationCounts[$service]['orders']; $i++) {
                ImportOrdersJob::dispatch($i, $service)
                    ->onQueue('crmimport')
                    ->delay(now()->addSeconds(5));
            }
        }

        return 0;
    }
}
