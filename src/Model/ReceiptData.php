<?php

namespace PaySelection\Model;

use stdClass;

class ReceiptData
{
    public ?string $timestamp   = null;
    public ?string $external_id = null;
    public ?stdClass $receipt   = null;
}
