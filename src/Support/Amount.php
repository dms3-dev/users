<?php

namespace Mediamouse\Users\Support;

class Amount {

    public static function format(float $amount): string
    {
        return number_format($amount, 2, ',', '');
    }

}
