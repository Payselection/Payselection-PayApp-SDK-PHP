<?php

namespace PaySelection\Enum;

use MyCLabs\Enum\Enum;


class PaymentType extends Enum
{
    public const PAY   = 'Pay';
    public const BLOCK = 'Block';
}
