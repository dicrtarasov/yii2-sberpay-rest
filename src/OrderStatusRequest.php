<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:10:28
 */

declare(strict_types = 1);
namespace dicr\sberpay;

/**
 * Запрос состояния платежа.
 */
class OrderStatusRequest extends SberPayRequest
{
    /**
     * Номер заказа в платежной системе.
     * В запросе должен присутствовать либо orderId, либо orderNumber. Если в запросе присутствуют оба параметра,
     * то приоритетным считается orderId.
     */
    public ?string $orderId = null;

    /**
     * Номер заказа в системе магазина.
     * В запросе должен присутствовать либо orderId, либо orderNumber. Если в запросе присутствуют оба параметра,
     * то приоритетным считается orderId.
     */
    public ?string $orderNumber = null;

    /**
     * Язык в кодировке ISO 639-1. Если не указан, будет использован язык, указанный в настройках
     * магазина как язык по умолчанию.
     */
    public ?string $language = null;

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
     * @inheritDoc
     */
    public function url(): string
    {
        return 'getOrderStatusExtended.do';
    }

    /**
     * @inheritDoc
     */
    public function send() : OrderStatusResponse
    {
        return new OrderStatusResponse([
            'json' => parent::send()
        ]);
    }
}
