<?php

namespace App\Services\ExternalPlatform;

use App\Constants\Order;
use App\Constants\PlatformTypes;
use App\Vendor\Autoweboffice\AwoApi;
use Carbon\Carbon;

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

    public function products(): array
    {
        $products = $this->apiClient()->product()->getAll();

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

    public function orders(): array
    {
        $orders = $this->apiClient()->invoice()->getAll(['id_account_status' => self::PLATFORM_PAID_STATUS]);

        return array_map(function ($order) {
            return [
                'client_id' => $order->id_contact,
//                'product_id' => $order->id,
                'price' => $order->account_sum,
                'status' => self::PLATFORM_STATUS_MAP[$order->id_account_status],
                'paid_at' => Carbon::parse($order->date_of_payment),
                'expired_at' => null,
                'platform' => PlatformTypes::AUTOWEBOFFICE,
                'platform_id' => $order->account_number
            ];
        }, array_filter($this->handleEmptyValue($orders), function ($order) {
            return in_array(
                $order->id_account_status,
                [self::PLATFORM_CANCEL_STATUS, self::PLATFORM_PAID_STATUS]
            );
        }));
    }

    public function clients(): array
    {
        $contacts = $this->apiClient()->contact()->getAll();

        return array_map(function ($contact) {
            return [
                'name' => $contact->name,
                'email' => $contact->email,
                'password' => $contact->password,
                'created_at' => Carbon::parse($contact->date_registration),
                'platform' => PlatformTypes::AUTOWEBOFFICE,
                'platform_id' => $contact->id_contact
            ];
        }, $this->handleEmptyValue($contacts));
    }

    public function handleEmptyValue($records): array
    {
        return !is_array($records) ? $records : [];
    }
}
