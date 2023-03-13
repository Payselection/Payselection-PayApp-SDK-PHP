<?php

namespace PaySelection\Response;

use PaySelection\Response\Helpers\StateDetails;
use PaySelection\Response\PSResponse;

class TransactionResponse extends PSResponse
{
    public ?string $transactionState   = null;
    public ?string $transactionId      = null;
    public ?string $orderId            = null;
    public ?StateDetails $stateDetails = null;

    public function get_helper_object() {
        return new StateDetails();
    }
}
