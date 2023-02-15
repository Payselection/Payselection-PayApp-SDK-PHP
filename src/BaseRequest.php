<?php

namespace PaySelection;

class BaseRequest
{
    public function roundNumber($num): string
    {
        return number_format($num, 2, '.', '');
    }
}