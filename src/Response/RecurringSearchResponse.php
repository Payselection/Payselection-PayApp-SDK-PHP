<?php

namespace PaySelection\Response;

use PaySelection\Response\PSResponse;

class RecurringSearchResponse extends PSResponse
{
    public ?string $rebillId           = null;
    public ?string $accountId          = null;
    public ?string $recurringId        = null;
    public ?string $orderId            = null;
    public ?string $recurringStatus    = null;
    public ?string $recurringAmount    = null;
    public ?string $recurringPaidCount = null;
    public ?string $recurringNextPay   = null;
}
