<?php

namespace PaySelection\Response;

use PaySelection\Response\PSResponse;

class ConfirmResponse extends PSResponse
{
    public string $transactionId;
    public string $orderId;
    public string $amount;
    public string $currency;
    public string $rebillId;
    
    public function fill($responseBody) {
        $this->transactionId = $responseBody->TransactionId;
        $this->orderId = $responseBody->OrderId;
        $this->amount = $responseBody->Amount;
        $this->currency = $responseBody->Currency;
        $this->rebillId = $responseBody->RebillId;
    }
}
