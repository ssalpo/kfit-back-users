<?php

namespace App\Services;

use App\Models\Order;
use App\Constants\Order as OrderConstant;

class OrderService
{
    /**
     * Changes the order status
     *
     * @param int $order
     * @param int $status
     * @return mixed
     */
    public function changeStatus(int $order, int $status)
    {
        $data = ['status' => $status];

        $order = Order::findOrFail($order);

        if ($this->isPaidStatus($status, $order->status)) {
            $data = array_merge($data, [
                'paid_at' => now(),
                'expired_at' => $order->product->expired_at
            ]);
        }

        $order->update($data);

        return $order->refresh();
    }

    /**
     * Check is order status changed to paid
     *
     * @param int $new
     * @param int $old
     * @return bool
     */
    private function isPaidStatus(int $new, int $old): bool
    {
        return $new !== $old && OrderConstant::STATUS_PAID === $new;
    }
}
