<?php

namespace PaySelection\Response;

use PaySelection\Model\Transaction\StateDetailsExt;
use PaySelection\Response\PSResponse;

class TransactionExtResponse extends PSResponse
{
    public ?string $transactionState      = null;
    public ?string $transactionId         = null;
    public ?string $orderId               = null;
    public ?StateDetailsExt $stateDetails = null;

    public function get_helper_object() {
        return new StateDetailsExt();
    }
}
