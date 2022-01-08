<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:29:48
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\sberpay\SberPayEntity;

/**
 * Информация о доставке.
 */
class DeliveryInfo extends SberPayEntity
{
    /** Город доставки. */
    public ?string $city = null;

    /** Страна доставки. */
    public ?string $country = null;

    /** Тип доставки. */
    public ?string $type = null;

    /** Адрес доставки. */
    public ?string $address = null;

    /**
     * @inheritDoc
     */
    public function attributeFields(): array
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
