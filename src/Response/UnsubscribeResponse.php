<?php

namespace PaySelection\Response;

use PaySelection\Response\Helpers\ErrorResponse;
use PaySelection\Response\PSResponse;

class UnsubscribeResponse extends PSResponse
{
    public ?string $transactionState = null;
    public ?ErrorResponse $error     = null;

    public function get_helper_object() {
        return new ErrorResponse();
    }
}
