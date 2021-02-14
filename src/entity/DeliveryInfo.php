<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:44:32
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\sberpay\SberPayEntity;

/**
 * Информация о доставке.
 */
class DeliveryInfo extends SberPayEntity
{
    /** @var string Город доставки. */
    public $city;

    /** @var string Страна доставки. */
    public $country;

    /** @var ?string Тип доставки. */
    public $type;

    /** @var string Адрес доставки. */
    public $address;

    /**
     * @inheritDoc
     */
    public function attributeFields() : array
    {
        return [
            'city' => 'delivery_city',
            'country' => 'delivery_country',
            'type' => 'delivery_type',
            'address' => 'post_address'
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['city', 'trim'],
            ['city', 'required'],

            ['country', 'trim'],
            ['country', 'required'],

            ['type', 'trim'],
            ['type', 'default'],

            ['address', 'trim'],
            ['address', 'required']
        ];
    }
}
