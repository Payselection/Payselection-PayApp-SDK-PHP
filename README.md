# Payselection api Library

## Оглавление

- [Установка](#установка)
- [Начало работы](#начало-работы)
- [Методы API](#методы-api)
    - [Create Webpay](#create-webpay)
    - [Get Order Status](#get-order-status)
    - [Get transaction Status](#get-transaction-status)
    - [Create Payment](#create-payment)
    - [Create Block](#create-block)
    - [Rebill Payment](#rebill-payment)
    - [Confirm Payment](#confirm-payment)
    - [Create Refund](#create-refund)
    - [Cancel Payment](#cancel-payment)
    - [Charge Payment](#charge-payment)
    - [Cancel Subscription](#cancel-subscription)
    - [Create Payout](#create-payout)
    - [Get Balance](#get-balance)
    - [Register Recurring](#register-recurring)
    - [Cancel Recurring](#cancel-recurring)
- [Работа с webhooks](#webhooks)
    - [Payments Webhook](#webhook)
    - [Recurring webhook](#recurring-webhook)

## Установка <a name="установка"></a>

Установить библиотеку можно с помощью composer:

```
composer require payselection/payselection-php-sdk
```

## Начало работы <a name="начало-работы"></a>

1. Создайте экземпляр объекта клиента.
```php
$apiClient = new \PaySelection\Library();
$apiClient->setConfiguration([
    'webpay_url' => 'https://webform.payselection.com',
    'api_url' => 'https://gw.payselection.com',
    'site_id' => '123',
    'secret_key' => '###########',
    'webhook_url' => 'https://webhook.site/notification/',
    'recurring_webhook_url' => 'https://webhook.site/notification/'
]);
```

Значение `webhook_url` должно совпадать со значением `WebhookUrl` из запросов

Значение `recurring_webhook_url` должно совпадать со значением `WebhookUrl` из запросов для Recurring

2. Вызовите нужный метод API. 

## Методы API <a name="методы-api"></a>

### Create Webpay <a name="create-webpay"></a>

[Create Webpay в документации](https://api.payselection.com/#operation/Create)

Создайте платёж, чтобы Покупатель смог оплатить его

```php
try {
    $response = $apiClient->createWebPay([
        'MetaData' => [
            'PaymentType' => 'Pay'
        ],
        'PaymentRequest' => [
            'OrderId' => 'a3a393d8-ac47-11ed-afa1-0242ac1200021',
            'Amount' => '100.00',
            'Currency' => 'RUB',
            'Description' => 'Order description',
            'RebillFlag' => true,
            'ExtraData' => [
                'WebhookUrl' => 'https://webhook.site/f2bea4b3-e85c-40e9-9587-b588cfda84d3'
            ],
        ],
        'ReceiptData' => [
            'timestamp' => '2023-01-11T13:38+0000',
            'external_id' => '12345678',
            'receipt' => [
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
            ]
        ],
        'CustomerInfo' => [
            'ZIP' => '222410',
            'Language' => 'RU'
        ],
        'RecurringData' => [
            'Amount' => '100.00',
            'Currency' => 'RUB',
            'Description' => 'Recurring description',
            'WebhookUrl' => 'https://webhook.site/f2bea4b3-e85c-40e9-9587-b588cfda84d3',
            'AccountId' => 'order63',
            'Email' => 'user@example.com',
            'StartDate' => '2023-05-11T13:38+0000',
            'Interval' => '5',
            'Period' => 'day',
            'MaxPeriods' => '3',
            'ReceiptData' => [
                'timestamp' => '2023-01-11T13:38+0000',
                'external_id' => '12345678',
                'receipt' => [
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
                ]
            ]
        ]
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Get Order Status <a name="get-order-status"></a>
[Статус ордера в документации](https://api.payselection.com/#operation//orders/{OrderId}:)

Получить статус ордера по OrderId.

```php
try {
    $response = $apiClient->getOrderStatus('a3a393d8-ac47-11ed-afa1-0242ac120002');
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Get transaction Status <a name="get-transaction-status"></a>

[Статус транзакции в документации](https://api.payselection.com/#operation//transactions/{transactionId}:)

Получить статус по TransactionId.

```php
try {
    $response = $apiClient->getTransactionStatus('PS00000000000001');
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Create Payment <a name="create-payment"></a>

[Operation Pay в документации](https://api.payselection.com/#operation/Pay)

Одностадийная операция оплаты – денежные средства списываются сразу после ее проведения.

```php
try {
    $response = $apiClient->createPayment([
        'OrderId' => 'a3a393d8-ac47-11ed-afa1-0242ac120002',
        'Amount' => '100.00',
        'Currency' => 'RUB',
        'Description' => 'Order description',
        'RebillFlag' => false,
        'CustomerInfo' => [
            'IP' => '192.168.1.10'
        ],
        'ExtraData' => [
            'WebhookUrl' => 'https://webhook.site/38fb8867-a648-423e-9146-30576b2ad8e4'
        ],
        'PaymentMethod' => 'Card',
        'ReceiptData' => [
            'timestamp' => '2023-01-11T13:38+0000',
            'external_id' => '12345678',
            'receipt' => [
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
            ]
        ],
        'PaymentDetails' => [
            'CardNumber'=> '4111111111111111',
            'ExpMonth'=> '02',
            'ExpYear'=> '25',
            'CardholderName'=> 'Card Holder',
            'CVC'=> '789'
        ]
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Create Block <a name="create-block"></a>

[Operation Block в документации](https://api.payselection.com/#operation/Block)

Двухстадийная операция оплаты – денежные средства блокируются на карте. Если авторизация прошла успешно, необходимо завершить транзакцию в течение 5 дней, если же вы не подтвердите транзакцию запросом на списание в течение 5 дней, снятие денежных средств будет автоматически отменено. Кроме того, есть возможность задать rebillFlag для включения рекуррентных платежей.

```php
try {
    $response = $apiClient->createBlock([
        'OrderId' => 'a3a393d8-ac47-11ed-afa1-0242ac120002',
        'Amount' => '100.00',
        'Currency' => 'RUB',
        'Description' => 'Order description',
        'RebillFlag' => false,
        'CustomerInfo' => [
            'IP' => '192.168.1.10'
        ],
        'ExtraData' => [
            'WebhookUrl' => 'https://webhook.site/38fb8867-a648-423e-9146-30576b2ad8e4'
        ],
        'PaymentMethod' => 'Card',
        'ReceiptData' => [
            'timestamp' => '2023-01-11T13:38+0000',
            'external_id' => '12345678',
            'receipt' => [
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
            ]
        ],
        'PaymentDetails' => [
            'CardNumber'=> '4111111111111111',
            'ExpMonth'=> '02',
            'ExpYear'=> '25',
            'CardholderName'=> 'Card Holder',
            'CVC'=> '789'
        ]
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Rebill Payment <a name="rebill-payment"></a>

[Operation Rebill в документации](https://api.payselection.com/#operation/Rebill)

Операция автоматического списания средств по привязанной ранее карте.

```php
try {
    $response = $apiClient->rebillPayment([
        'OrderId' => 'a3a393d8-ac47-11ed-afa1-0242ac120002',
        'Amount' => '1.00',
        'Currency' => 'RUB',
        'Description' => 'Order description',
        'RebillFlag' => true,
        'RebillId' => 'GE00000001173680',
        'ReceiptData' => [
            'timestamp' => '2023-01-11T13:38+0000',
            'external_id' => '12345678',
            'receipt' => [
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
            ]
        ],
        'WebhookUrl' => 'https://webhook.site/38fb8867-a648-423e-9146-30576b2ad8e4'
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Confirm Payment <a name="confirm-payment"></a>

[Operation Confirm в документации](https://api.payselection.com/#operation/Confirm)

Используется для операций Pay или Block с 3DS после получения результатов аутентификации от банка для завершения одностадийной/двухстадийной операции оплаты.

```php
try {
    $response = $apiClient->сonfirmPayment([
        'TransactionId' => 'PS00000000000001',
        'OrderId' => 'a3a393d8-ac47-11ed-afa1-0242ac120002',
        'PaRes' => '123',
        'MD' => '456'
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Create Refund <a name="create-refund"></a>

[Operation Refund в документации](https://api.payselection.com/#operation/Refund)

Только успешная транзакция может быть возвращена

```php
try {
    $response = $apiClient->createRefund([
        'TransactionId' => 'PS00000000000001',
        'Amount' => '1.00',
        'Currency' => 'RUB',
        'ReceiptData' => [
            'timestamp' => '2023-01-11T13:38+0000',
            'external_id' => '12345678',
            'receipt' => [
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
            ]
        ],
        'WebhookUrl' => 'https://webhook.site/38fb8867-a648-423e-9146-30576b2ad8e4'
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Cancel Payment <a name="cancel-payment"></a>

[Operation Cancel в документации](https://api.payselection.com/#operation/Cancel)

Отмена блокировки средств на карте в рамках ранее проведенной двухстадийной операции оплаты.

```php
try {
    $response = $apiClient->cancelPayment([
            'TransactionId' => 'PS00000000000001',
            'Amount' => '100.00',
            'Currency' => 'RUB',
            'WebhookUrl' => 'https://webhook.site/38fb8867-a648-423e-9146-30576b2ad8e4'
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Charge Payment <a name="charge-payment"></a>

[Operation Charge в документации](https://api.payselection.com/#operation/Charge)

Списание средств с карты в рамках проведенной ранее двухстадийной операции оплаты.

```php
try {
    $response = $apiClient->chargePayment([
        'TransactionId' => 'PS00000000000001',
        'Amount' => '100.00',
        'Currency' => 'RUB',
        'WebhookUrl' => 'https://webhook.site/38fb8867-a648-423e-9146-30576b2ad8e4'
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Cancel Subscription <a name="cancel-subscription"></a>

[Operation Unsubscribe в документации](https://api.payselection.com/#operation/Unsubscribe)

Отмена рекуррентных платежей.При использовании данного метода произойдет отписка по всем зарегистрированным регулярным оплатам в рамках переданного RebillId

```php
try {
    $response = $apiClient->cancelSubscription([
        'RebillId' => 'GE00000001173680'
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Create Payout <a name="create-payout"></a>

[Operation Payout в документации](https://api.payselection.com/#operation/Payout)

Одностадийная операция оплаты – денежные средства списываются сразу после ее проведения.

```php
try {
    $response = $apiClient->createPayout([
        'OrderId' => 'a3a393d8-ac47-11ed-afa1-0242ac120002',
        'Amount' => '100.00',
        'Currency' => 'RUB',
        'Description' => 'Order description',
        'CustomerInfo' => [
            'IP' => '192.168.1.10'
        ],
        'ExtraData' => [
            'WebhookUrl' => 'https://webhook.site/38fb8867-a648-423e-9146-30576b2ad8e4'
        ],
        'PayoutMethod' => 'Card',
        'ReceiptData' => [
            'timestamp' => '2023-01-11T13:38+0000',
            'external_id' => '12345678',
            'receipt' => [
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
            ]
        ],
        'PayoutDetails' => [
            'CardNumber'=> '4111111111111111',
            'CardholderName'=> 'Card Holder',
        ]
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Get Balance <a name="get-balance"></a>

[Operation Balance в документации](https://api.payselection.com/#operation/Balance)

Операция проверки доступного баланса для Payout.

```php
try {
    $response = $apiClient->getBalance();
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```
### Register Recurring <a name="register-recurring"></a>

[Operation Recurring в документации](https://api.payselection.com/#operation/Recurring)

Регистрация регулярной оплаты по привязанной ранее карте.

```php
try {
    $response = $apiClient->registerRecurring([
        'RebillId' => 'GE00000001173680',
        'Amount' => '100.00',
        'Currency' => 'RUB',
        'Description' => 'Recurring description',
        'WebhookUrl' => 'https://webhook.site/f2bea4b3-e85c-40e9-9587-b588cfda84d3',
        'AccountId' => 'order63',
        'Email' => 'user@example.com',
        'StartDate' => '2023-05-11T13:38+0000',
        'Interval' => '5',
        'Period' => 'day',
        'MaxPeriods' => '3',
        'ReceiptData' => [
            'timestamp' => '2023-01-11T13:38+0000',
            'external_id' => '12345678',
            'receipt' => [
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
            ]
        ]
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Cancel Recurring <a name="cancel-recurring"></a>

[Operation Recurring Unsubscribe в документации](https://api.payselection.com/#operation/Recurring%20Unsubscribe)

Отмена регулярной оплаты.

```php
try {
    $response = $apiClient->cancelRecurring([
        'RebillId' => 'GE00000001173680',
        'RecurringId' => '1173',
        'AccountId' => 'order63',
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

## Работа с webhooks <a name="webhooks"></a>

### Webhook <a name="webhook"></a>

[Webhook в документации](https://api.payselection.com/#operation/Webhooks)

```php
try {
    $result = $apiClient->hookPay();
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($result);
```

### Webhook <a name="recurring-webhook"></a>

[Webhook для подписок в документации](https://api.payselection.com/#tag/webhooks-dlya-podpisok)

```php
try {
    $result = $apiClient->hookRecurring();
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($result);
```

## License

MIT
