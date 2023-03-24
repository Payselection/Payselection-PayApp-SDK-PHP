<?php

namespace PaySelection\Response;

use PaySelection\Response\PSResponse;

class RecurringCancelResponse extends PSResponse
{
    public ?string $recurringId = null;
}
