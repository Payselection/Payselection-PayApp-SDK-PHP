# Payselection api Library

## Оглавление

- [Установка](#установка)
- [Начало работы](#начало-работы)
- [Методы API](#методы-api)
    - [Create Webpay](#create-webpay)
    - [Get Order Status](#get-order-status)
    - [Get Order Status (extended)](#get-order-status-extended)
    - [Get transaction Status](#get-transaction-status)
    - [Get transaction Status (extended)](#get-transaction-status-extended)
    - [Get transaction Status (by-dates)](#get-transaction-status-by-dates)
    - [Paylink Create](#create-paylink)
    - [Paylink Void](#void-paylink)
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
    - [Search Recurring](#search-recurring)
    - [Change Recurring](#change-recurring)
    - [Cancel Recurring](#cancel-recurring)
- [Работа с webhooks](#webhooks)
- [Webhook для проверки платежа](#verify-payment-webhooks)

## Установка <a name="установка"></a>

Установить библиотеку можно с помощью composer:

```
composer require payselection/payselection-php-client
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
    'public_key' => '###########',
    'webhook_url' => 'https://webhook.site/notification/'
]);
```

Значение `webhook_url` должно совпадать со значением `WebhookUrl` из запросов.
Значение `public_key` может использоваться в методах Paylink и Webpay.

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
            'Language' => 'ru'
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

### Paylink Create <a name="create-paylink"></a>

[Paylink Create в документации](https://api.payselection.com/#operation/Paylink%20Create)

Метод позволяет создать ссылку для перехода на платежный виджет.

```php
try {
    $response = $apiClient->createPaylink([
        'MetaData' => [
            'PaymentType' => 'Pay',
            'TypeLink' => 'Reusable',
            'PreviewForm' => true,
            'SendSMS' => true,
            'OfferUrl' => 'string',
            'SendBill' => true
        ],
        'PaymentRequest' => [
            'OrderId' => 'string',
            'Amount' => '123.45',
            'Currency' => 'RUB',
            'Description' => 'string',
            'RebillFlag' => false,
            'ExtraData' => [
                'ReturnUrl' => 'https://api.payselection.com/',
                'SuccessUrl' => 'string',
                'DeclineUrl' => 'string',
                'WebhookUrl' => 'https://webhook.site/94a06b69',
                'ShortDescription' => 'string',
                'DynamicAmount' => true
            ]
        ],
        'ReceiptData' => [
            'timestamp' => 'string',
            'external_id' => 'string',
            'receipt' => [
                'client' => [
                    'name' => 'string',
                    'inn' => 'string',
                    'email' => 'string',
                    'phone' => 'string'
                ],
                'company' => [
                    'email' => 'string',
                    'sno' => 'osn',
                    'inn' => 'string',
                    'payment_address' => 'string'
                ],
                'agent_info' => [
                    'type' => 'bank_paying_agent',
                    'paying_agent' => [
                        'operation' => 'string',
                        'phones' => [
                            'string'
                        ]
                    ],
                    'receive_payments_operator' => [
                        'phones' => [
                            'string'
                        ]
                    ],
                    'money_transfer_operator' => [
                        'phones' => [
                            'string'
                        ],
                        'name' => 'string',
                        'address' => 'string',
                        'inn' => 'string'
                    ]
                ],
                'supplier_info' => [
                    'phones' => [
                        'string'
                    ]
                ],
                'items' => [
                    [
                        'name' => 'string',
                        'price' => 42949673,
                        'quantity' => 99999.999,
                        'sum' => 42949672.95,
                        'measurement_unit' => 'string',
                        'payment_method' => 'full_prepayment',
                        'payment_object' => 'commodity',
                        'nomenclature_code' => 'string',
                        'vat' => [
                            'type' => 'none',
                            'sum' => 99999999.99
                        ],
                        'agent_info' => [
                            'type' => 'bank_paying_agent',
                            'paying_agent' => [
                                'operation' => 'string',
                                'phones' => [
                                    'string'
                                ]
                            ],
                            'receive_payments_operator' => [
                                'phones' => [
                                    'string'
                                ]
                            ],
                            'money_transfer_operator' => [
                                'phones' => [
                                    'string'
                                ],
                                'name' => 'string',
                                'address' => 'string',
                                'inn' => 'string'
                            ]
                        ],
                        'supplier_info' => [
                            'phones' => [
                                'string'
                            ],
                            'name' => 'string',
                            'inn' => 'string'
                        ],
                        'user_data' => 'string',
                        'excise' => 0,
                        'country_code' => 'str',
                        'declaration_number' => 'string'
                    ]
                ],
                'payments' => [
                    [
                        'type' => 0,
                        'sum' => 99999999.99
                    ]
                ],
                'vats' => [
                    [
                        'type' => 'none',
                        'sum' => 99999999.99
                    ]
                ],
                'total' => 99999999.99,
                'additional_check_props' => 'string',
                'cashier' => 'string',
                'additional_user_props' => [
                    'name' => 'string',
                    'value' => 'string'
                ]
            ]
        ],
        'CustomerInfo' => [
            'Email' => 'user@example.com',
            'ReceiptEmail' => 'user@example.com',
            'Phone' => '+79999999999',
            'Language' => 'en',
            'Address' => 'string',
            'Town' => 'string',
            'ZIP' => 'string',
            'Country' => 'RUS',
            'UserId' => 'string'
        ],
        'RecurringData' => [
            'Amount' => '123.45',
            'Currency' => 'RUB',
            'Description' => 'string',
            'WebhookUrl' => 'https://webhook.site/94a06b69',
            'AccountId' => 'order63',
            'Email' => 'user@example.com',
            'StartDate' => '2023-01-11T13:38+0000',
            'Interval' => '5',
            'Period' => 'day',
            'MaxPeriods' => '3',
            'ReceiptData' => [
                'timestamp' => 'string',
                'external_id' => 'string',
                'receipt' => [
                    'client' => [
                        'name' => 'string',
                        'inn' => 'string',
                        'email' => 'string',
                        'phone' => 'string'
                    ],
                    'company' => [
                        'email' => 'string',
                        'sno' => 'osn',
                        'inn' => 'string',
                        'payment_address' => 'string'
                    ],
                    'agent_info' => [
                        'type' => 'bank_paying_agent',
                        'paying_agent' => [
                            'operation' => 'string',
                            'phones' => [
                                'string'
                            ]
                        ],
                        'receive_payments_operator' => [
                            'phones' => [
                                'string'
                            ]
                        ],
                        'money_transfer_operator' => [
                            'phones' => [
                                'string'
                            ],
                            'name' => 'string',
                            'address' => 'string',
                            'inn' => 'string'
                        ]
                    ],
                    'supplier_info' => [
                        'phones' => [
                            'string'
                        ]
                    ],
                    'items' => [
                        [
                            'name' => 'string',
                            'price' => 42949673,
                            'quantity' => 99999.999,
                            'sum' => 42949672.95,
                            'measurement_unit' => 'string',
                            'payment_method' => 'full_prepayment',
                            'payment_object' => 'commodity',
                            'nomenclature_code' => 'string',
                            'vat' => [
                                'type' => 'none',
                                'sum' => 99999999.99
                            ],
                            'agent_info' => [
                                'type' => 'bank_paying_agent',
                                'paying_agent' => [
                                    'operation' => 'string',
                                    'phones' => [
                                        'string'
                                    ]
                                ],
                                'receive_payments_operator' => [
                                    'phones' => [
                                        'string'
                                    ]
                                ],
                                'money_transfer_operator' => [
                                    'phones' => [
                                        'string'
                                    ],
                                    'name' => 'string',
                                    'address' => 'string',
                                    'inn' => 'string'
                                ]
                            ],
                            'supplier_info' => [
                                'phones' => [
                                    'string'
                                ],
                                'name' => 'string',
                                'inn' => 'string'
                            ],
                            'user_data' => 'string',
                            'excise' => 0,
                            'country_code' => 'str',
                            'declaration_number' => 'string'
                        ]
                    ],
                    'payments' => [
                        [
                            'type' => 0,
                            'sum' => 99999999.99
                        ]
                    ],
                    'vats' => [
                        [
                            'type' => 'none',
                            'sum' => 99999999.99
                        ]
                    ],
                    'total' => 99999999.99,
                    'additional_check_props' => 'string',
                    'cashier' => 'string',
                    'additional_user_props' => [
                        'name' => 'string',
                        'value' => 'string'
                    ]
                ]
            ]
        ],
        'ExtendedData' => [
            'FIO' => [
                'enabled' => true,
                'required' => true
            ],
            'Phone' => [
                'enabled' => true,
                'required' => true
            ],
            'Email' => [
                'enabled' => true,
                'required' => true
            ],
            'TokenLifeTime' => 10,
            'Custom' => [
                'enabled' => true,
                'required' => true,
                'name' => 'string'
            ]
        ]
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Paylink Void <a name="void-paylink"></a>

[Paylink Void в документации](https://api.payselection.com/#operation/Paylink%20Void)

Метод позволяет отменить ссылку на платежный виджет.

```php
try {
    $response = $apiClient->createPaylinkVoid([
        'Id' => 'string'
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Get Order Status <a name="get-order-status"></a>
[Статус ордера в документации](https://api.payselection.com/#operation/OrderId)

Получить статус ордера по OrderId.

```php
try {
    $response = $apiClient->getOrderStatus('a3a393d8-ac47-11ed-afa1-0242ac120002');
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Get Order Status (extended) <a name="get-order-status-extended"></a>
[Статус ордера в документации](https://api.payselection.com/#operation/OrderId%20(extended))

Расширенный запрос используется для получения информации о текущем статусе по идентификатору заказа orderId.

```php
try {
    $response = $apiClient->getOrderStatusExt('a3a393d8-ac47-11ed-afa1-0242ac120002');
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Get transaction Status <a name="get-transaction-status"></a>

[Статус транзакции в документации](https://api.payselection.com/#operation/TransactionId)

Получить статус по TransactionId.

```php
try {
    $response = $apiClient->getTransactionStatus('PS00000000000001');
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Get transaction Status (extended) <a name="get-transaction-status-extended"></a>

[Статус транзакции в документации](https://api.payselection.com/#operation/TransactionId%20(extended))

Расширенный запрос используется для получения информации о текущем статусе по идентификатору транзакции TransactionId.

```php
try {
    $response = $apiClient->getTransactionStatusExt('PS00000000000001');
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Get transaction Status (by-dates) <a name="get-transaction-status-by-dates"></a>

[Статус транзакции в документации](https://api.payselection.com/#operation/Transactions%20(by-dates))

Расширенный запрос используется для получения статуса транзакций по выбранному диапазону дат.

```php
try {
    $response = $apiClient->getTransactionStatusByDates([
        'StartCreationDate' => '2022-12-31T00:00:00',
        'EndCreationDate' => '2023-12-31T00:00:00',
        'PageNumber' => 1,
        'TimeZone' => 'Africa/Abidjan',
        'Statuses' => [
            'success',
            'voided',
            'preauthorized',
            'pending',
            'declined',
            'wait_for_3ds',
            'redirect'
        ]
    ]);
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
    $response = $apiClient->confirmPayment([
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
        'RebillId' => 'PS00000000000001'
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
        'RecurringId' => '1173'
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Search Recurring <a name="search-recurring"></a>

[Operation Recurring Search в документации](https://api.payselection.com/#operation/Recurring%20Search)

Поиск регулярной оплаты (подписки) по выбранному параметру.

```php
try {
    $response = $apiClient->searchRecurring([
        'RebillId' => 'PS00000000000001',
        'RecurringId' => '1173',
        'AccountId' => 'order63',
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

### Change Recurring <a name="change-recurring"></a>

[Operation Recurring Change в документации](https://api.payselection.com/#operation/Recurring%20Change)

Изменение параметров регулярной оплаты (подписки).

```php
try {
    $response = $apiClient->changeRecurring([
        'RecurringId' => '1173',
        'MaxPeriods' => '3',
        'StartDate' => '2023-01-11T13:38+0000',
        'Interval' => '5',
        'Period' => 'day',
        'Amount' => '123.45',
        'ReceiptData' => [
            'timestamp' => 'string',
            'external_id' => 'string',
            'receipt' => [
                'client' => [
                    'name' => 'string',
                    'inn' => 'string',
                    'email' => 'string',
                    'phone' => 'string'
                ],
                'company' => [
                    'email' => 'string',
                    'sno' => 'osn',
                    'inn' => 'string',
                    'payment_address' => 'string'
                ],
                'agent_info' => [
                    'type' => 'bank_paying_agent',
                    'paying_agent' => [
                        'operation' => 'string',
                        'phones' => [
                            'string'
                        ]
                    ],
                    'receive_payments_operator' => [
                        'phones' => [
                            'string'
                        ]
                    ],
                    'money_transfer_operator' => [
                        'phones' => [
                            'string'
                        ],
                        'name' => 'string',
                        'address' => 'string',
                        'inn' => 'string'
                    ]
                ],
                'supplier_info' => [
                    'phones' => [
                        'string'
                    ]
                ],
                'items' => [
                    [
                        'name' => 'string',
                        'price' => 42949673,
                        'quantity' => 99999.999,
                        'sum' => 42949672.95,
                        'measurement_unit' => 'string',
                        'payment_method' => 'full_prepayment',
                        'payment_object' => 'commodity',
                        'nomenclature_code' => 'string',
                        'vat' => [
                            'type' => 'none',
                            'sum' => 99999999.99
                        ],
                        'agent_info' => [
                            'type' => 'bank_paying_agent',
                            'paying_agent' => [
                                'operation' => 'string',
                                'phones' => [
                                    'string'
                                ]
                            ],
                            'receive_payments_operator' => [
                                'phones' => [
                                    'string'
                                ]
                            ],
                            'money_transfer_operator' => [
                                'phones' => [
                                    'string'
                                ],
                                'name' => 'string',
                                'address' => 'string',
                                'inn' => 'string'
                            ]
                        ],
                        'supplier_info' => [
                            'phones' => [
                                'string'
                            ],
                            'name' => 'string',
                            'inn' => 'string'
                        ],
                        'user_data' => 'string',
                        'excise' => 0,
                        'country_code' => 'str',
                        'declaration_number' => 'string'
                    ]
                ],
                'payments' => [
                    [
                        'type' => 0,
                        'sum' => 99999999.99
                    ]
                ],
                'vats' => [
                    [
                        'type' => 'none',
                        'sum' => 99999999.99
                    ]
                ],
                'total' => 99999999.99,
                'additional_check_props' => 'string',
                'cashier' => 'string',
                'additional_user_props' => [
                    'name' => 'string',
                    'value' => 'string'
                ]
            ]
        ]
    ]);
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($response);
```

## Работа с webhooks <a name="webhooks"></a>

[Webhook в документации](https://api.payselection.com/#operation/Webhooks)
[Webhook для подписок в документации](https://api.payselection.com/#tag/webhooks-dlya-podpisok)

```php
try {
    $result = $apiClient->hookPay();
} catch (\Exception $e) {
    $response = $e->getMessage();
}

var_dump($result);
```

## Работа с webhooks проверки платежа <a name="verify-payment-webhooks"></a>

[Webhook для проверки платежа в документации](https://api.payselection.com/#tag/Webhook-proverki-platezha)

После ввода клиентом карточных данных вы можете получить webhook с данными из запроса для сопоставления их с данными заказа.
Вебхук проверки платежа отличается от обычного вебхука, в котором ТСП получает результаты платежа, после его проведения. Вебхук проверки платежа активируется только технической поддержкой. Чтобы включить, настроить и выключить вебхук проверки платежа необходимо написать на почту support@payselection.com.

В письме сообщите:

1. URL-адрес возврата, куда будет передаваться вебхук. Также вы можете заранее самостоятельно настроить статичный URL возврата в личном кабинете, в разделе "Сайты". Укажите его для тех.поддержки.
2. Параметры из запроса, которые вам необходимо проверить (это всё, что входит в payment request в методе create)

Возможные варианты параметров для проверки:
- сумма
- номер заказа
- валюта платежа

Проверочный вебхук отправляется с webpay после ввода карточных данных или выбора клиентом иного способа оплаты.
Вебхук проверки платежа приходит в формате json и содержит только те параметры для проверки, которые вы указали в письме

Пример содержимого полученного вебхука:
{"OrderId": "1299670125", "Amount": "4.50", "Description": "Description", "RebillFlag": false}

После подключения вебхука проверки платежа, когда вы направите запрос с необходимыми параметрами и URL возврата, Ваш сервис приема должен отдавать:

1. 200 статус, если оплату можно продолжать
2. 4хх и 5хх статусы в различных вариациях для прерывания оплаты.

```php
try {
    $result = $apiClient->verifyPaymentHook();
    // Пример проверки суммы
    if ($result->amount != 500) throw new Exception("Incorrect amount");
} catch (\PaySelection\Exceptions\PSException $e) {
    print_r($e);
}

var_dump($result);
```

## License

MIT
