<?php

namespace PaySelection\Response;

use PaySelection\Response\PSResponse;

class TransactionResponse extends PSResponse
{
    public string $transactionState;
    public string $transactionId;
    public string $orderId;
    public object $stateDetails;
    public string $amount;
    public string $currency;
    public string $processingAmount;
    public string $remainingAmount;

    public function fill($responseBody) {
        $this->transactionState = $responseBody->TransactionState;
        $this->transactionId = $responseBody->TransactionId;
        $this->orderId = $responseBody->OrderId;
        $this->stateDetails = $responseBody->StateDetails;
        $this->stateDetails->amount = $responseBody->StateDetails->Amount;
        $this->stateDetails->currency = $responseBody->StateDetails->Currency;
        $this->stateDetails->processingAmount = $responseBody->StateDetails->ProcessingAmount;
        $this->stateDetails->remainingAmount = $responseBody->StateDetails->RemainingAmount;
    }
}
