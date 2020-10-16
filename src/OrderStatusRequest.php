<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 14:58:56
 */

declare(strict_types = 1);
namespace dicr\sberbank;

/**
 * Запрос состояния платежа.
 */
class OrderStatusRequest extends SberbankRequest
{
    /**
     * @var ?string Номер заказа в платежной системе.
     * В запросе должен присутствовать либо orderId, либо orderNumber. Если в запросе присутствуют оба параметра,
     * то приоритетным считается orderId.
     */
    public $orderId;

    /**
     * @var ?string Номер заказа в системе магазина.
     * В запросе должен присутствовать либо orderId, либо orderNumber. Если в запросе присутствуют оба параметра,
     * то приоритетным считается orderId.
     */
    public $orderNumber;

    /**
     * @var ?string Язык в кодировке ISO 639-1. Если не указан, будет использован язык, указанный в настройках
     * магазина как язык по умолчанию.
     */
    public $language;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return array_merge(parent::rules(), [
            [['orderId', 'orderNumber', 'language'], 'trim'],
            [['orderId', 'orderNumber', 'language'], 'default'],

            ['orderId', 'required', 'when' => function () : bool {
                return empty($this->orderNumber);
            }],

            ['orderNumber', 'required', 'when' => function () : bool {
                return empty($this->orderId);
            }]
        ]);
    }

    /**
     * @return string
     */
    public static function url() : string
    {
        return 'getOrderStatusExtended.do';
    }

    /**
     * @inheritDoc
     * @return OrderStatusResponse
     */
    public function send() : OrderStatusResponse
    {
        return new OrderStatusResponse([
            'json' => parent::send()
        ]);
    }
}
