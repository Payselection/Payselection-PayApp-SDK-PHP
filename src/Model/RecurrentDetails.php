<?php

namespace PaySelection\Model;

use PaySelection\Model\Transaction\TransactionStateDetails;

class RecurrentDetails
{
    public ?string $transactionState                         = null;
    public ?string $transactionId                            = null;
    public ?TransactionStateDetails $transactionStateDetails = null;
}
