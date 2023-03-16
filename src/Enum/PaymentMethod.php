<?php

namespace PaySelection\Enum;

use MyCLabs\Enum\Enum;


class PaymentMethod extends Enum
{
    public const CARD   = 'Card';
    public const QIWI = 'Qiwi';
    public const TOKEN = 'Token';
}
