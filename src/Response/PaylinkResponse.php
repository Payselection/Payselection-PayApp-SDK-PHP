<?php

namespace PaySelection\Response;

use PaySelection\Response\PSResponse;

class PaylinkResponse extends PSResponse
{
    public ?string $id = null;
    public ?string $status       = null;
    public ?string $url        = null;
    public ?string $invoice      = null;
    public ?string $email      = null;
    public ?string $phone      = null;
    public ?string $description      = null;
    public ?string $amount      = null;
    public ?string $currency      = null;
    public ?string $createdDate      = null;
    public ?string $typeLink      = null;
}
