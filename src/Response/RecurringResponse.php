<?php

namespace PaySelection\Response;

use PaySelection\Response\PSResponse;

class RecurringResponse extends PSResponse
{
    public ?string $accountId   = null;
    public ?string $amount      = null;
    public ?string $currency    = null;
    public ?string $recurringId = null;
    public ?string $rebillId           = null;
    public ?string $orderId            = null;
    public ?string $recurringStatus    = null;
    public ?string $recurringAmount    = null;
    public ?string $recurringPaidCount = null;
    public ?string $recurringNextPay   = null;
}
