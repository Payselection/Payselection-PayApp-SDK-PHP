<?php

namespace PaySelection\Response;

use PaySelection\Response\Helpers\ErrorResponse;
use PaySelection\Response\PSResponse;

class BalanceResponse extends PSResponse
{
    public ?string $transactionState = null;
    public ?string $description      = null;
    public ?string $balance          = null;
    public ?string $currency         = null;
    public ?ErrorResponse $error     = null;

    public function get_helper_object() {
        return new ErrorResponse();
    }
}
