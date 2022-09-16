<?php

namespace App\Constants;

interface Order
{
    const STATUS_PAID = 1;
    const STATUS_NOT_PAID = 2;
    const STATUS_CANCELED = 3;

    const ALL_STATUSES = [
        self::STATUS_PAID,
        self::STATUS_NOT_PAID,
        self::STATUS_CANCELED,
    ];
}
