<?php

namespace PaySelection\Enum;

use MyCLabs\Enum\Enum;


class PaymentMethod extends Enum
{
    public const CARD   = 'Card';
    public const CRYPTOGRAM = 'Cryptogram';
    public const TOKEN = 'Token';
    public const QR = 'QR';
    public const SBERPAY = 'SberPay';
}
