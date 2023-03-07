<?php

namespace PaySelection\Enum;

use MyCLabs\Enum\Enum;

class PSMethodsEnum extends Enum
{
    /**
     * Создание Webpay платежа
     */
    public const PAYMENTS_WEBPAY = 'webpayments/create';

    /**
     * Operation Pay
     */
    public const PAYMENTS_PAY = 'payments/requests/single';

    /**
     * Operation Refund
     */
    public const PAYMENTS_REFUND = 'payments/refund';

    /**
     * Operation Block
     */
    public const PAYMENTS_BLOCK = 'payments/requests/block';

    /**
     * Operation Confirm
     */
    public const PAYMENTS_CONFIRM = 'payments/confirmation';

    /**
     * Operation Charge
     */
    public const PAYMENTS_CHARGE = 'payments/charge';

    /**
     * Operation Cancel
     */
    public const PAYMENTS_CANCEL = 'payments/cancellation';

    /**
     * Get transaction status
     */
    public const TRANSACTION_STATUS = 'transactions';
}
