# Payselection api Library

## Оглавление

- [Установка](#установка)
- [Начало работы](#начало-работы)
- [Операции](#операции)

## Установка

Установить библиотеку можно с помощью composer:

```
composer require payselection/payselection-php-sdk
```

## Начало работы

1. Создайте экземпляр объекта клиента.
```php
$apiClient = new \PaySelection\Library();
$apiClient->setConfiguration(array(
    'webpay_url' => 'https://webform.payselection.com',
    'api_url' => 'https://gw.payselection.com',
    'site_id' => '123',
    'secret_key' => '###########',
    'webhook_url' => 'https://webhook.site/notification/'));
```
2. Вызовите нужный метод API. 

## Методы API

### Webpay create

[Webpay create](https://api.payselection.com/#operation/Create)

Создайте платёж, чтобы Покупатель смог оплатить его

```php
$response = $apiClient->webPayCreate(
    100,
    'RUB',
    'a3a393d8-ac47-11ed-afa1-0242ac120002',
    'item #144',
    array(
        'WebhookUrl' => 'https://webhook.site/38fb8867-a648-423e-9146-30576b2ad8e4'
    ),
    array(
        'ZIP' => '222410',
        'Language' => 'RU'
    ),
    array(
        'client' => [
            'name' => 'Inan Ivanov',
            'email' => 'ivan@example.com',
        ],
        'company' => [
            'email' => 'company@example.com',
            'inn' => '12345',
            'payment_address' => 'company address',
        ],
        'items' => [
            [
                'name' => 'Product title 1',
                'price' => 123.00,
                'quantity' => 1,
                'sum' => 123.00,
                'payment_method' => 'full_prepayment',
                'payment_object' => 'commodity',
                'vat' => [
                    'type' => 'vat0'
                ]
            ],
            [
                'name' => 'Product title 2',
                'price' => 10.00,
                'quantity' => 2,
                'sum' => 20.00,
                'payment_method' => 'full_prepayment',
                'payment_object' => 'commodity',
                'vat' => [
                    'type' => 'vat0'
                ]
                ],
        ],
        'payments' => [
            [
                'type' => 1, 
                'sum' => 143.00
            ]
        ],
        'total' => 143.00     
    )
);

echo $response->redirectUrl;
```
### Get transaction Status

[Статус транзакции](https://api.payselection.com/#operation//transactions/{transactionId}:)

Получить статус по TransactionId.

```php
try {
    $response = $apiClient->getTransactionStatus('PS00000000000001');
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Pay

[Operation Pay](https://api.payselection.com/#operation/Pay)

Одностадийная операция оплаты – денежные средства списываются сразу после ее проведения.

```php
try {
    $response = $apiClient->createPayment(
        array(
            'OrderId' => 'a3a393d8-ac47-11ed-afa1-0242ac120002',
            'Amount' => '100.00',
            'Currency' => 'RUB',
            'Description' => 'Order description',
            'RebillFlag' => false,
            'CustomerInfo' => array(
                'IP' => '192.168.1.10'
            ),
            'ExtraData' => array(
                'WebhookUrl' => 'https://webhook.site/38fb8867-a648-423e-9146-30576b2ad8e4'
            ),
            'PaymentMethod' => 'Card',
            'ReceiptData' => array(
                'timestamp' => '12345',
                'external_id' => '12345678',
                'receipt' => array(
                    'client' => [
                        'name' => 'Inan Ivanov',
                        'email' => 'ivan@example.com',
                    ],
                    'company' => [
                        'email' => 'company@example.com',
                        'inn' => '12345',
                        'payment_address' => 'company address',
                    ],
                    'items' => [
                        [
                            'name' => 'Product title 1',
                            'price' => 123.00,
                            'quantity' => 1,
                            'sum' => 123.00,
                            'payment_method' => 'full_prepayment',
                            'payment_object' => 'commodity',
                            'vat' => [
                                'type' => 'vat0'
                            ]
                        ],
                        [
                            'name' => 'Product title 2',
                            'price' => 10.00,
                            'quantity' => 2,
                            'sum' => 20.00,
                            'payment_method' => 'full_prepayment',
                            'payment_object' => 'commodity',
                            'vat' => [
                                'type' => 'vat0'
                            ]
                            ],
                    ],
                    'payments' => [
                        [
                            'type' => 1, 
                            'sum' => 143.00
                        ]
                    ],
                    'total' => 143.00     
                )
            ),
            'PaymentDetails' => array(
                'CardNumber'=> '4111111111111111',
                'ExpMonth'=> '02',
                'ExpYear'=> '25',
                'CardholderName'=> 'Card Holder',
                'CVC'=> '789'
            )
        )
    );
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Block

[Operation Block](https://api.payselection.com/#operation/Block)

Двухстадийная операция оплаты – денежные средства блокируются на карте. Если авторизация прошла успешно, необходимо завершить транзакцию в течение 5 дней, если же вы не подтвердите транзакцию запросом на списание в течение 5 дней, снятие денежных средств будет автоматически отменено. Кроме того, есть возможность задать rebillFlag для включения рекуррентных платежей.

```php
try {
    $response = $apiClient->createBlock(
        array(
            'OrderId' => 'a3a393d8-ac47-11ed-afa1-0242ac120002',
            'Amount' => '100.00',
            'Currency' => 'RUB',
            'Description' => 'Order description',
            'RebillFlag' => false,
            'CustomerInfo' => array(
                'IP' => '192.168.1.10'
            ),
            'ExtraData' => array(
                'WebhookUrl' => 'https://webhook.site/38fb8867-a648-423e-9146-30576b2ad8e4'
            ),
            'PaymentMethod' => 'Card',
            'ReceiptData' => array(
                'timestamp' => '12345',
                'external_id' => '12345678',
                'receipt' => array(
                    'client' => [
                        'name' => 'Inan Ivanov',
                        'email' => 'ivan@example.com',
                    ],
                    'company' => [
                        'email' => 'company@example.com',
                        'inn' => '12345',
                        'payment_address' => 'company address',
                    ],
                    'items' => [
                        [
                            'name' => 'Product title 1',
                            'price' => 123.00,
                            'quantity' => 1,
                            'sum' => 123.00,
                            'payment_method' => 'full_prepayment',
                            'payment_object' => 'commodity',
                            'vat' => [
                                'type' => 'vat0'
                            ]
                        ],
                        [
                            'name' => 'Product title 2',
                            'price' => 10.00,
                            'quantity' => 2,
                            'sum' => 20.00,
                            'payment_method' => 'full_prepayment',
                            'payment_object' => 'commodity',
                            'vat' => [
                                'type' => 'vat0'
                            ]
                            ],
                    ],
                    'payments' => [
                        [
                            'type' => 1, 
                            'sum' => 143.00
                        ]
                    ],
                    'total' => 143.00     
                )
            ),
            'PaymentDetails' => array(
                'CardNumber'=> '4111111111111111',
                'ExpMonth'=> '02',
                'ExpYear'=> '25',
                'CardholderName'=> 'Card Holder',
                'CVC'=> '789'
            )
        )
    );
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Refund

[Operation Refund](https://api.payselection.com/#operation/Refund)

Только успешная транзакция может быть возвращена

```php
try {
    $response = $apiClient->createRefund(
        array(
            'TransactionId' => 'PS00000000000001',
            'Amount' => '1.00',
            'Currency' => 'RUB',
            'ReceiptData' => array(
                'timestamp' => '12345',
                'external_id' => '12345678',
                'receipt' => array(
                    'client' => [
                        'name' => 'Inan Ivanov',
                        'email' => 'ivan@example.com',
                    ],
                    'company' => [
                        'email' => 'company@example.com',
                        'inn' => '12345',
                        'payment_address' => 'company address',
                    ],
                    'items' => [
                        [
                            'name' => 'Product title 1',
                            'price' => 123.00,
                            'quantity' => 1,
                            'sum' => 123.00,
                            'payment_method' => 'full_prepayment',
                            'payment_object' => 'commodity',
                            'vat' => [
                                'type' => 'vat0'
                            ]
                        ],
                        [
                            'name' => 'Product title 2',
                            'price' => 10.00,
                            'quantity' => 2,
                            'sum' => 20.00,
                            'payment_method' => 'full_prepayment',
                            'payment_object' => 'commodity',
                            'vat' => [
                                'type' => 'vat0'
                            ]
                            ],
                    ],
                    'payments' => [
                        [
                            'type' => 1, 
                            'sum' => 143.00
                        ]
                    ],
                    'total' => 143.00     
                )
            ),
            'WebhookUrl' => 'https://webhook.site/38fb8867-a648-423e-9146-30576b2ad8e4'
        )
    );
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Confirm

[Operation Confirm](https://api.payselection.com/#operation/Confirm)

Используется для операций Pay или Block с 3DS после получения результатов аутентификации от банка для завершения одностадийной/двухстадийной операции оплаты.

```php
try {
    $response = $apiClient->сonfirmPayment(
        array(
            'TransactionId' => 'PS00000000000001',
            'OrderId' => 'a3a393d8-ac47-11ed-afa1-0242ac120002',
            'PaRes' => '123',
            'MD' => '456'
        )
    );
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Charge

[Operation Charge](https://api.payselection.com/#operation/Charge)

Списание средств с карты в рамках проведенной ранее двухстадийной операции оплаты.

```php
try {
    $response = $apiClient->chargePayment(
        array(
            'TransactionId' => 'PS00000000000001',
            'Amount' => '100.00',
            'Currency' => 'RUB',
            'WebhookUrl' => 'https://webhook.site/38fb8867-a648-423e-9146-30576b2ad8e4'
        )
    );
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Cancel

[Operation Cancel](https://api.payselection.com/#operation/Cancel)

Отмена блокировки средств на карте в рамках ранее проведенной двухстадийной операции оплаты.

```php
try {
    $response = $apiClient->cancelPayment(
        array(
            'TransactionId' => 'PS00000000000001',
            'Amount' => '100.00',
            'Currency' => 'RUB',
            'WebhookUrl' => 'https://webhook.site/38fb8867-a648-423e-9146-30576b2ad8e4'
        )
    );
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

## Работа с webhooks

```php
$result = $apiClient->hookPay();

echo $result->event;
```
Значение `webhook_url` должно совпадать со значением `WebhookUrl` из запроса **Create**

## License

MIT
