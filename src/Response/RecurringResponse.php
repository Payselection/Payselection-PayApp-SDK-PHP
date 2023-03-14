<?php

namespace PaySelection\Response;

use PaySelection\Response\PSResponse;

class RecurringResponse extends PSResponse
{
    public ?string $accountId   = null;
    public ?string $amount      = null;
    public ?string $currency    = null;
    public ?string $recurringId = null;
}
