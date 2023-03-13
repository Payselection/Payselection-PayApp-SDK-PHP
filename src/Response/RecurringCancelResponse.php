<?php

namespace PaySelection\Response;

use PaySelection\Response\PSResponse;

class RecurringCancelResponse extends PSResponse
{
    public ?string $accountId   = null;
    public ?string $rebillId    = null;
    public ?string $recurringId = null;
}
