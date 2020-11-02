<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 14:10:40
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\SberbankEntity;

/**
 * Информация о доставке.
 */
class DeliveryInfo extends SberbankEntity
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
    public static function attributeFields() : array
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
