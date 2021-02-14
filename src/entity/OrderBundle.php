<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:30:42
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\json\EntityValidator;
use dicr\sberpay\SberpayEntity;

/**
 * Информация о заказе.
 */
class OrderBundle extends SberpayEntity
{
    /** @var ?string Дата создания заказа в формате YYYY-MM-DD\THH:mm:ss. */
    public $orderCreationDate;

    /** @var ?CustomerDetails Блок с атрибутами данных о покупателе. */
    public $customerDetails;

    /** @var CartItems Блок с атрибутами товарных позиции корзины товаров. */
    public $cartItems;

    /**
     * @inheritDoc
     */
    public function attributeEntities() : array
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
