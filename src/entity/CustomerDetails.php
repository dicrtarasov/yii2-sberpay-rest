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
use dicr\sberpay\PhoneValidator;
use dicr\sberpay\SberpayEntity;
use dicr\validate\InnValidator;

/**
 * Class CustomerDetails
 */
class CustomerDetails extends SberpayEntity
{
    /** @var ?string Способ связи с покупателем. */
    public $contact;

    /**
     * @var string Адрес электронной почты покупателя.
     * Можно указать несколько адресов электронной почты через запятую и без пробелов -
     * в этом случае чек будет отправлен на все указанные адреса.
     */
    public $email;

    /**
     * @var ?string Номер телефона клиента.
     * Если в телефон включён код страны, номер должен начинаться со знака плюс («+»).
     * Если телефон передаётся без знака плюс («+»), то код страны указывать не следует.
     * Допустимое количество цифр: от 7 до 15.
     */
    public $phone;

    /** @var ?string Фамилия, имя и отчество плательщика. Строка (до 100 символов) */
    public $fullName;

    /** @var ?int Серия и номер паспорта плательщика в следующем формате: 2222888888. */
    public $passport;

    /** @var ?int Идентификационный номер налогоплательщика. Допускается передавать 10 или 12 символов. */
    public $inn;

    /** @var ?DeliveryInfo Блок с атрибутами адреса для доставки. */
    public $deliveryInfo;

    /**
     * @inheritDoc
     */
    public function attributeFields() : array
    {
        return [
            'deliveryInfo' => 'delivery_info'
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeEntities() : array
    {
        return [
            'deliveryInfo' => DeliveryInfo::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['contact', 'trim'],
            ['contact', 'default'],

            ['email', 'trim'],
            ['email', 'required'],

            ['phone', 'trim'],
            ['phone', PhoneValidator::class],
            ['phone', 'default'],

            ['fullName', 'trim'],
            ['fullName', 'default'],
            ['fullName', 'string', 'max' => 100],

            ['passport', 'trim'],
            ['passport', 'default'],
            ['passport', 'integer', 'min' => 1111111111, 'max' => 9999999999],
            ['passport', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],

            ['inn', 'default'],
            ['inn', InnValidator::class, 'skipOnEmpty' => true],

            ['deliveryInfo', 'default'],
            ['deliveryInfo', EntityValidator::class],
        ];
    }
}
