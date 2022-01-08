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
use dicr\sberpay\PhoneValidator;
use dicr\sberpay\SberPayEntity;
use dicr\validate\InnValidator;

/**
 * Class CustomerDetails
 */
class CustomerDetails extends SberPayEntity
{
    /** Способ связи с покупателем. */
    public ?string $contact = null;

    /**
     * Адрес электронной почты покупателя.
     * Можно указать несколько адресов электронной почты через запятую и без пробелов -
     * в этом случае чек будет отправлен на все указанные адреса.
     */
    public ?string $email = null;

    /**
     * Номер телефона клиента.
     * Если в телефон включён код страны, номер должен начинаться со знака плюс («+»).
     * Если телефон передаётся без знака плюс («+»), то код страны указывать не следует.
     * Допустимое количество цифр: от 7 до 15.
     */
    public ?string $phone = null;

    /** Фамилия, имя и отчество плательщика. Строка (до 100 символов) */
    public ?string $fullName = null;

    /** Серия и номер паспорта плательщика в следующем формате: 2222888888. */
    public ?int $passport = null;

    /** Идентификационный номер налогоплательщика. Допускается передавать 10 или 12 символов. */
    public ?int $inn = null;

    /** Блок с атрибутами адреса для доставки. */
    public DeliveryInfo|array|null $deliveryInfo = null;

    /**
     * @inheritDoc
     */
    public function attributeFields(): array
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
