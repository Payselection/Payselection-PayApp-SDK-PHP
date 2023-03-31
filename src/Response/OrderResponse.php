<?php

namespace PaySelection\Response;

use PaySelection\Response\PSResponse;
use PaySelection\Response\TransactionResponse;
use Psr\Http\Message\ResponseInterface;

class OrderResponse extends PSResponse
{
    public ?int $transactionsCount = null;
    public ?array $transactions    = null;

    public function fillByResponse(ResponseInterface $response): self
    {
        $responseContent = json_decode($response->getBody(), true);
        if ($responseContent && is_array($responseContent)) {
            $this->transactionsCount = count($responseContent);
            foreach ($responseContent as $item) {
                $transaction = new TransactionResponse();
                $this->transactions[] = $transaction->fill($item);
            }
        }
        return $this;
    }
}
