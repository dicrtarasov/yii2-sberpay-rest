<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:18:57
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\SberbankEntity;
use dicr\validate\ValidateException;

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

            ['customerDetails', function (string $attribute) {
                if (empty($this->customerDetails)) {
                    $this->customerDetails = null;
                } elseif (! $this->customerDetails instanceof CustomerDetails) {
                    $this->addError($attribute);
                } elseif (! $this->customerDetails->validate()) {
                    $this->addError($attribute, (new ValidateException($this->customerDetails))->getMessage());
                }
            }],

            ['cartItems', function (string $attribute) {
                if (empty($this->cartItems)) {
                    $this->cartItems = null;
                } elseif (! $this->cartItems instanceof CartItems) {
                    $this->addError($attribute);
                } elseif (! $this->cartItems->validate()) {
                    $this->addError($attribute, (new ValidateException($this->cartItems))->getMessage());
                }
            }]
        ];
    }
}
