<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:44:44
 */

declare(strict_types = 1);
namespace dicr\tests;

use dicr\sberpay\OrderStatusResponse;
use dicr\sberpay\SberPayModule;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\Exception;

use function time;

/**
 * Class SberpayModuleTest
 */
class SberpayModuleTest extends TestCase
{
    /**
     * Модуль.
     *
     * @return SberPayModule
     */
    private static function module(): SberPayModule
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Yii::$app->getModule('sberpay');
    }

    /**
     *
     * @throws Exception
     * @noinspection PhpUnitMissingTargetForTestInspection
     */
    public function testRegister() : void
    {
        $orderNumber = time();
        $amount = 3982;

        $req = self::module()->registerPaymentRequest([
            'orderNumber' => $orderNumber,
            //'amount' => $amount, авто-вычисление
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
                            //'amount' => 1509.765 ( 1510 копеек ) авто-вычисление
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
