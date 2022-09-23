<?php

namespace App\Jobs;

use App\Constants\PlatformTypes;
use App\Models\Client;
use App\Models\Product;
use App\Services\ExternalCrmImport\ExternalServiceManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ImportOrdersJob implements ShouldQueue
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

        $orders = $service->orders($this->page);

        $platformProductIds = array_unique(array_column($orders, 'product_id'));
        $platformClientIds = array_unique(array_column($orders, 'client_id'));

        $products = Product::whereIn('platform_id', $platformProductIds)->pluck('id', 'platform_id');
        $clients = Client::whereHas('platformClients', function ($q) use ($platformClientIds) {
            $q->whereIn('platform_id', $platformClientIds);
        })->pluck('id', 'platform_id');

        foreach ($orders as $order) {
            DB::table('orders')->updateOrInsert(
                Arr::only($order, ['platform', 'platform_id']),
                array_merge($order, [
                    'client_id' => $clients[$order['client_id']],
                    'product_id' => $products[$order['product_id']]
                ])
            );
        }
    }
}
