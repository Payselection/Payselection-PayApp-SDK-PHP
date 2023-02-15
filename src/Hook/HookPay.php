<?php

namespace PaySelection\Hook;

use PaySelection\BaseHook;

/**
 *
 */
class HookPay extends BaseHook
{
    // Required

    public ?int    $transactionId = null;
    public ?float  $amount        = null;
    public ?string $currency      = null;


    /**
     *
     */
    public function handle()
    {

    }
}
