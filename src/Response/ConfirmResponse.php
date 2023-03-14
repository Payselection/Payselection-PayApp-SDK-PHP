<?php

namespace PaySelection\Response;

use PaySelection\Response\PSResponse;

class ConfirmResponse extends PSResponse
{
    public ?string $transactionId = null;
    public ?string $orderId       = null;
    public ?string $amount        = null;
    public ?string $currency      = null;
    public ?string $rebillId      = null;
}
