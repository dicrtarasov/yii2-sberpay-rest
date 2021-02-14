# Сбербанк REST API для Yii2

## Настройка

```php
$config = [
    'modules' => [
        'sberbank' => [
            'class' => dicr\sberbank\SberbankModule::class,
            'userName' => 'user-api',
            'password' => 'my-password'
        ]
    ]       
];
```

## Использование

### Создание платежа

```php
/** @var dicr\sberbank\SberbankModule $module */
$module = Yii::$app->getModule('sberbank');

/** @var dicr\sberbank\RegisterPaymentRequest $request создаем запрос */
$request = $module->registerPaymentRequest([
    'orderNumber' => '<номер заказа магазина>',
    //'amount' => 3982, // авто-вычисление
    'returnUrl' => 'https://test.ru',
    'orderBundle' => [
        'cartItems' => [
            'items' => [
                [
                    'positionId' => 1,
                    'name' => 'Русская водка',
                    'code' => 'VODKA-777',
                    'price' => 1203,
                    'quantity' => ['value' => 1.255, 'measure' => 'л'] // чекушка 1.25 литров
                    //'amount' => 1509.765 ( 1510 копеек ) авто-вычисление с округлением
                ],
                [
                    'positionId' => 2,
                    'name' => 'Пряная селедка',
                    'code' => 'СЕЛ-Д',
                    'price' => 1236, // 12.34 руб
                    'quantity' => ['value' => 2, 'measure' => 'шт'],
                    'amount' => 2472
                ],
            ]
        ]
    ]
]);

/** @var dicr\sberbank\RegisterPaymentResponse $response отправляем запрос */
$response = $request->send();

/** @var string $orderId номер заказа в системе банка */
$orderId = $response->orderId;

// переадресуем посетителя на страницу оплаты
$response->redirect();
```

### Получение статуса платежа

```php
/** @var dicr\sberbank\SberbankModule $module */
$module = Yii::$app->getModule('sberbank');

/** @var dicr\sberbank\OrderStatusRequest $req создаем запрос */
$req = $module->orderStatusRequest([
    'orderId' => '<номер заказа магазина>'
]);

/** @var dicr\sberbank\OrderStatusResponse $res отправляем запрос */
$res = $req->send();

/** @var int $orderStatus статус заказа */
$orderStatus = $res->orderStatus;
```

