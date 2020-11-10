<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 10.11.20 17:21:26
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\json\EntityValidator;
use dicr\sberbank\SberbankEntity;

/**
 * Информация о заказе.
 */
class OrderBundle extends SberbankEntity
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
