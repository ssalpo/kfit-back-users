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
    protected $signature = 'kfit:import:crm {--except=}';

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
        $exceptHandlers = array_filter(explode(',', $this->option('except')));

        foreach (ExternalServiceManager::SERVICE_LIST as $service) {
            $paginationService = ExternalServiceManager::makePagination($service);

            if (!in_array('products', $exceptHandlers)) {
                for ($i = 1; $i <= $paginationService->products(); $i++) {
                    ImportProductsJob::dispatch($i, $service)
                        ->onQueue('crmimport')
                        ->delay(now()->addSeconds(5));
                }
            }

            if (!in_array('clients', $exceptHandlers)) {
                for ($i = 1; $i <= $paginationService->clients(); $i++) {
                    ImportClientsJob::dispatch($i, $service)
                        ->onQueue('crmimport')
                        ->delay(now()->addSeconds(5));
                }
            }

            if (!in_array('orders', $exceptHandlers)) {
                for ($i = 1; $i <= $paginationService->orders(); $i++) {
                    ImportOrdersJob::dispatch($i, $service)
                        ->onQueue('crmimport')
                        ->delay(now()->addSeconds(5));
                }
            }
        }

        return 0;
    }
}
