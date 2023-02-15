<?php

namespace PaySelection\Enum;

use MyCLabs\Enum\Enum;

class PSMethodsEnum extends Enum
{
    /**
     * Создание Webpay платежа
     */
    public const PAYMENTS_WEBPAY = 'webpayments/create';
}
