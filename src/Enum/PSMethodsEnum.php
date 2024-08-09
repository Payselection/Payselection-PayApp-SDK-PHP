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
     * Paylink Create
     */
    public const PAYMENTS_PAYLINK_CREATE = 'webpayments/paylink_create';

    /**
     * Paylink Void
     */
    public const PAYMENTS_PAYLINK_VOID = 'webpayments/paylink_void';

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
    public const TRANSACTION_STATUS = 'transactions/%s';

    /**
     * Get transaction status (extended)
     */
    public const TRANSACTION_STATUS_EXT = 'transactions/%s/extended';

    /**
     * Transactions (by-dates)
     */
    public const TRANSACTIONS_BY_DATE_STATUS = 'transactions/by-dates';

    /**
     * Get order status
     */
    public const ORDER_STATUS = 'orders/%s';

    /**
     * Get order status (extended)
     */
    public const ORDER_STATUS_EXT = 'orders/%s/extended';

    /**
     * Operation Rebill
     */
    public const PAYMENTS_REBILL = 'payments/requests/rebill';

    /**
     * Operation Unsubscribe
     */
    public const PAYMENTS_UNSUBSCRIBE = 'payments/unsubscribe';

    /**
     * Operation Recurring
     */
    public const PAYMENTS_RECURRING = 'payments/recurring';

    /**
     * Operation Recurring Unsubscribe
     */
    public const PAYMENTS_RECURRING_UNSUBSCRIBE = 'payments/recurring/unsubscribe';

    /**
     * Operation Recurring Change
     */
    public const PAYMENTS_RECURRING_CHANGE = 'payments/recurring/change';

    /**
     * Operation Recurring Search
     */
    public const PAYMENTS_RECURRING_SEARCH = 'payments/recurring/search';

    /**
     * Operation Balance
     */
    public const BALANCE = 'balance';

    /**
     * Operation Payout
     */
    public const PAYMENTS_PAYOUT = 'payouts';
}
