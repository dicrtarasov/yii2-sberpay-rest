<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 07.03.21 17:35:26
 */

declare(strict_types = 1);
namespace dicr\sberpay;

/**
 * Запрос состояния платежа.
 */
class OrderStatusRequest extends SberPayRequest
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

            ['orderId', 'required', 'when' => fn(): bool => empty($this->orderNumber)],

            ['orderNumber', 'required', 'when' => fn(): bool => empty($this->orderId)]
        ]);
    }

    /**
     * @return string
     */
    public function url(): string
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
