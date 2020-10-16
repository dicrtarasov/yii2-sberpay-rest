<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 15:08:52
 */

declare(strict_types = 1);
namespace dicr\tests;

use dicr\sberbank\OrderStatusResponse;
use dicr\sberbank\SberbankModule;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\Exception;

/**
 * Class SberbankModuleTest
 */
class SberbankModuleTest extends TestCase
{
    /**
     * Модуль.
     *
     * @return SberbankModule
     */
    private static function module() : SberbankModule
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Yii::$app->getModule('sberbank');
    }

    /**
     *
     * @throws Exception
     */
    public function testRegister() : void
    {
        $orderNumber = time();
        $amount = 3982;

        $req = self::module()->registerPaymentRequest([
            'orderNumber' => $orderNumber,
            //'amount' => $amount, автовычисление
            'returnUrl' => 'https://ekoyar.ru',
            'orderBundle' => [
                'cartItems' => [
                    'items' => [
                        [
                            'positionId' => 1,
                            'name' => 'Русская водка',
                            'code' => 'VODKA-777',
                            'price' => 1203,
                            'quantity' => ['value' => 1.255, 'measure' => 'л'] // чекушка 1.25 литров
                            //'amount' => 1509.765 ( 1510 копеек ) автовычисление
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

        self::assertTrue($req->validate());
        self::assertSame($amount, $req->amount);

        $res = $req->send();
        self::assertEmpty($res->errorCode);
        self::assertNotEmpty($res->orderId);
        self::assertNotEmpty($res->formUrl);
        echo 'orderId: ' . $res->orderId . "\n";
        echo 'formUrl: ' . $res->formUrl . "\n";

        $req = self::module()->orderStatusRequest([
            'orderId' => $res->orderId,
        ]);

        self::assertTrue($req->validate());

        $res = $req->send();
        self::assertSame((int)$res->orderNumber, $orderNumber);
        self::assertSame($res->amount, $amount);
        self::assertSame($res->orderStatus, OrderStatusResponse::STATUS_REGISTERED);
    }
}
