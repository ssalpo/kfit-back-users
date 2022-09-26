<?php

namespace App\Services\ExternalCrmImport\Importers;

use App\Constants\Order;
use App\Constants\PlatformTypes;
use App\Services\ExternalCrmImport\Contracts\ImportContract;
use App\Vendor\Autoweboffice\AwoApi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AutowebofficeService implements ImportContract
{
    const PLATFORM_PAID_STATUS = 5;
    const PLATFORM_CANCEL_STATUS = 2;

    const PLATFORM_STATUS_MAP = [
        self::PLATFORM_PAID_STATUS => Order::STATUS_PAID,
        self::PLATFORM_CANCEL_STATUS => Order::STATUS_CANCELED,
    ];

    public function apiClient(): AwoApi
    {
        return new AwoApi(config('services.autoweboffice'));
    }

    public function products(int $page): array
    {
        $products = $this->apiClient()->product()->page($page)->pageSize(1000)->getAll();

        return array_map(function ($product) {
            return [
                'title' => $product->goods,
                'description' => $product->brief_description,
                'price' => $product->price,
                'expired_at' => null,
                'created_at' => Carbon::parse($product->creation_date),
                'platform' => PlatformTypes::AUTOWEBOFFICE,
                'platform_id' => $product->id_goods
            ];
        }, $this->handleEmptyValue($products));
    }

    public function orders(int $page): array
    {
        $invoices = $this->handleEmptyValue($this->apiClient()->invoice()->page($page)->pageSize(1000)->getAll());

        $invoiceIds = collect($invoices)->pluck('account_number')->unique();

        $relatedGoods = [];

        foreach ($invoiceIds->chunk(50)->toArray() as $ids) {
            $relations = collect(
                $this->handleEmptyValue($this->apiClient()->invoiceLine()->getAll(['id_account' => $ids]))
            )->pluck('id_goods', 'id_account')->toArray();

            foreach ($relations as $account => $good) {
                $relatedGoods[$account] = $good;
            }
        }

        return collect($invoices)
            ->whereIn('id_account_status', [self::PLATFORM_CANCEL_STATUS, self::PLATFORM_PAID_STATUS])
            ->map(function ($invoice) use ($relatedGoods) {
                return [
                    'client_id' => $invoice->id_contact,
                    'product_id' => $relatedGoods[$invoice->account_number],
                    'price' => $invoice->account_sum,
                    'status' => self::PLATFORM_STATUS_MAP[$invoice->id_account_status],
                    'paid_at' => Carbon::parse($invoice->date_of_payment),
                    'expired_at' => null,
                    'platform' => PlatformTypes::AUTOWEBOFFICE,
                    'platform_id' => $invoice->account_number
                ];
            })
            ->toArray();
    }

    public function clients(int $page): array
    {
        $contacts = $this->apiClient()->contact()->page($page)->pageSize(1000)->getAll();

        return array_map(function ($contact) {
            return [
                'name' => $contact->name,
                'email' => $contact->email,
                'created_at' => Carbon::parse($contact->date_registration),
                'platform' => PlatformTypes::AUTOWEBOFFICE,
                'platform_id' => $contact->id_contact
            ];
        }, $this->handleEmptyValue($contacts));
    }

    private function handleEmptyValue($records): array
    {
        return is_array($records) ? $records : [];
    }
}
