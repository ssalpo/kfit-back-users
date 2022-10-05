<?php

namespace App\Constants;

interface GoodsType
{
    const MARATHON = 1;
    const COURSE = 2;
    const WORKOUT = 3;

    const ALL = [
        self::MARATHON,
        self::COURSE,
        self::WORKOUT,
    ];
}
