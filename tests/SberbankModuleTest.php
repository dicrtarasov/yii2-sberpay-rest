<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 10:25:54
 */

declare(strict_types = 1);
namespace dicr\tests;

use dicr\sberbank\SberbankModule;
use PHPUnit\Framework\TestCase;
use Yii;

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
     */
    public function testRegister() : void
    {
        $orderNumber = time();

        $req = self::module()->registerPaymentRequest([
            'orderNumber' => $orderNumber,
            //'amount' => 3982,
            'returnUrl' => 'https://dicr.org',
            'orderBundle' => [
                'cartItems' => [
                    'items' => [
                        [
                            'positionId' => 2,
                            'name' => 'Русская водка',
                            'code' => 'VODKA-777',
                            'price' => 1203,
                            'quantity' => ['value' => 1.255, 'measure' => 'л'] // чекушка 1.25 литров
                            //'amount' => 1509.765 ( 1510 копеек )
                        ],
                        [
                            'positionId' => 1,
                            'name' => 'Селедка сущеная',
                            'code' => 'СЕЛ-Д',
                            'price' => 1236, // 12.34 руб
                            'quantity' => ['value' => 2, 'measure' => 'шт'],
                            'amount' => 2472
                        ],
                    ]
                ]
            ]
        ]);

        $req->validate();
        var_dump($req);
        exit;
    }
}
