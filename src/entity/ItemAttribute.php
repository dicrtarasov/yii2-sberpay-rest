<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 04:55:30
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\json\EntityValidator;
use dicr\sberbank\SberbankEntity;

/**
 * Аттрибуты товара.
 */
class ItemAttribute extends SberbankEntity
{
    /** @var int полная предварительная оплата до момента передачи предмета расчёта */
    public const PAYMENT_FULL_BEFORE = 1;

    /** @var int частичная предварительная оплата до момента передачи предмета расчёта */
    public const PAYMENT_PART_BEFORE = 2;

    /** @var int аванс */
    public const PAYMENT_PREPAY = 3;

    /** @var int полная оплата в момент передачи предмета расчёта */
    public const PAYMENT_FULL_AFTER = 4;

    /** @var int частичная оплата предмета расчёта в момент его передачи с последующей оплатой в кредит */
    public const PAYMENT_PART_AFTER = 5;

    /** @var int передача предмета расчёта без его оплаты в момент его передачи с последующей оплатой в кредит */
    public const PAYMENT_NONE_CREDIT = 6;

    /** @var int оплата предмета расчёта после его передачи с оплатой в кредит */
    public const PAYMENT_AFTER_CREDIT = 7;

    /** @var int товар */
    public const OBJECT_PRODUCT = 1;

    /** @var int подакцизный товар */
    public const OBJECT_EXCISABLE_PRODUCT = 2;

    /** @var int работа */
    public const OBJECT_WORK = 3;

    /** @var int услуга */
    public const OBJECT_SERVICE = 4;

    /** @var int ставка азартной игры */
    public const OBJECT_GAMBLING_RATE = 5;

    /** @var int выигрыш азартной игры */
    public const OBJECT_GAMBLING_WIN = 6;

    /** @var int лотерейный билет */
    public const OBJECT_LOTTERY_TICKET = 7;

    /** @var int выигрыш лотереи */
    public const OBJECT_LOTTERY_WIN = 8;

    /** @var int предоставление РИД */
    public const OBJECT_PROVISION_RIA = 9;

    /** @var int платеж */
    public const OBJECT_PAYMENT = 10;

    /** @var int агентское вознаграждение */
    public const OBJECT_AGENT_COMMISSION = 11;

    /** @var int составной предмет расчёта */
    public const OBJECT_COMPOUND = 12;

    /** @var int иной предмет расчёта */
    public const OBJECT_OTHER = 13;

    /** @var int имущественное право */
    public const OBJECT_PROPERTY = 14;

    /** @var int внереализационный доход */
    public const OBJECT_NON_OPERATING = 15;

    /** @var int страховые взносы */
    public const OBJECT_INSURANCE = 16;

    /** @var int торговый сбор */
    public const OBJECT_TRADE = 17;

    /** @var int курортный сбор */
    public const OBJECT_RESORT = 18;

    /**
     * @var int Тип оплаты (PAYMENT_*)
     * Значением по умолчанию является 1 (полная предварительная оплата до момента передачи предмета расчета).
     */
    public $paymentMethod;

    /** @var int Тип оплачиваемой позиции. Значением по умолчанию является 1 (товар) */
    public $paymentObject;

    /** @var ?string Код товарной позиции в текстовом представлении. Максимальная длина – 32 байта. */
    public $nomenclature;

    /** @var ?string Значение реквизита пользователя. Можно передавать только после согласования с ФНС. */
    public $userData;

    /** @var ?AgentInfo агент */
    public $agentInfo;

    /** @var ?SupplierInfo поставщик */
    public $supplierInfo;

    /**
     * @inheritDoc
     */
    public function attributeFields() : array
    {
        return [
            'agentInfo' => 'agent_info',
            'supplierInfo' => 'supplier_info'
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeEntities() : array
    {
        return [
            'agentInfo' => AgentInfo::class,
            'supplierInfo' => SupplierInfo::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['paymentMethod', 'required'],
            ['paymentMethod', 'in', 'range' => [self::PAYMENT_FULL_BEFORE, self::PAYMENT_PART_BEFORE,
                self::PAYMENT_PREPAY, self::PAYMENT_FULL_AFTER, self::PAYMENT_PART_AFTER, self::PAYMENT_NONE_CREDIT,
                self::PAYMENT_AFTER_CREDIT]],
            ['paymentMethod', 'filter', 'filter' => 'intval'],

            ['paymentObject', 'required'],
            ['paymentObject', 'in', 'range' => [self::OBJECT_PRODUCT, self::OBJECT_EXCISABLE_PRODUCT,
                self::OBJECT_WORK, self::OBJECT_SERVICE, self::OBJECT_GAMBLING_RATE, self::OBJECT_GAMBLING_WIN,
                self::OBJECT_LOTTERY_TICKET, self::OBJECT_LOTTERY_WIN, self::OBJECT_PROVISION_RIA,
                self::OBJECT_PAYMENT, self::OBJECT_AGENT_COMMISSION, self::OBJECT_COMPOUND, self::OBJECT_OTHER,
                self::OBJECT_PROPERTY, self::OBJECT_NON_OPERATING, self::OBJECT_INSURANCE, self::OBJECT_TRADE,
                self::OBJECT_RESORT]],
            ['paymentObject', 'filter', 'filter' => 'intval'],

            ['nomenclature', 'trim'],
            ['nomenclature', 'default'],
            ['nomenclature', 'string', 'max' => 32],

            ['userData', 'trim'],
            ['userData', 'default'],

            ['agentInfo', 'default'],
            ['agentInfo', EntityValidator::class],

            ['supplierInfo', 'default'],
            ['supplierInfo', EntityValidator::class]
        ];
    }
}
