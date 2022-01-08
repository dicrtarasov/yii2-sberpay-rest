<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:29:48
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\json\EntityValidator;
use dicr\sberpay\SberPayEntity;

/**
 * Информация о заказе.
 */
class OrderBundle extends SberPayEntity
{
    /** Дата создания заказа в формате YYYY-MM-DD\THH:mm:ss. */
    public ?string $orderCreationDate = null;

    /** Блок с атрибутами данных о покупателе. */
    public CustomerDetails|array|null $customerDetails = null;

    /** Блок с атрибутами товарных позиции корзины товаров. */
    public CartItems|array|null $cartItems = null;

    /**
     * @inheritDoc
     */
    public function attributeEntities(): array
    {
        return [
            'customerDetails' => CustomerDetails::class,
            'cartItems' => CartItems::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['orderCreationDate', 'trim'],
            ['orderCreationDate', 'default'],
            ['orderCreationDate', 'date', 'format' => 'php:Y-m-d\TH:i:s'],

            ['customerDetails', 'default'],
            ['customerDetails', EntityValidator::class],

            ['cartItems', 'default'],
            ['cartItems', EntityValidator::class],
        ];
    }
}
