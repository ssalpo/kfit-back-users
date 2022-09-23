<?php

namespace App\Services\ExternalCrmImport\Importers;

use App\Constants\Order;
use App\Constants\PlatformTypes;
use App\Services\ExternalCrmImport\Contracts\ImportContract;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class GurucanService implements ImportContract
{

    public function apiClient(): PendingRequest
    {
        return Http::withHeaders(['Gc-api-key' => config('services.gurucan.key')])
            ->baseUrl(sprintf("https://%s.gurucan.com/api/admin/", config('services.gurucan.subdomain')));
    }

    public function products(int $page): array
    {
        $records = $this->apiClient()->get('users')->json('data', []);

        $product = collect($records)->filter(function ($record) {
                return isset($record['purchasedItems']) && count($record['purchasedItems']);
            })->first()['purchasedItems'][0]['_id'] ?? null;

        return [
            [
                'title' => $product['name'],
                'description' => $product['name'],
                'price' => $product['price'],
                'expired_at' => null,
                'created_at' => Carbon::parse($product['createdAt']),
                'platform' => PlatformTypes::GURUCAN,
                'platform_id' => $product['_id']
            ]
        ];
    }

    public function orders(int $page): array
    {
        $records = array_filter(
            $this->apiClient()->get('users')->json('data', []),
            function ($record) {
                return isset($record['purchasedItems']) &&
                    count($record['purchasedItems']) &&
                    isset($record['purchasedItems'][0]['addedAt']);
            }
        );

        return array_map(function ($record) {
            $product = $record['purchasedItems'][0];

            return [
                'client_id' => $record['_id'],
                'product_id' => $product['_id']['_id'],
                'price' => $product['_id']['price'],
                'status' => Order::STATUS_PAID,
                'paid_at' => Carbon::parse($product['addedAt']),
                'expired_at' => Carbon::parse($product['expiredAt']),
                'platform' => PlatformTypes::AUTOWEBOFFICE,
                'platform_id' => $product['payment']
            ];
        }, $records);
    }

    public function clients(int $page): array
    {
        $records = $this->apiClient()->get('users')->json('data', []);

        return array_map(function ($record) {
            return [
                'name' => $record['name'],
                'email' => $record['email'],
                'phone' => $record['phone'] ?? null,
                'password' => $record['phone'] ?? null,
                'created_at' => Carbon::parse($record['createdAt']),
                'platform' => PlatformTypes::GURUCAN,
                'platform_id' => $record['_id']
            ];
        }, $records);
    }
}
