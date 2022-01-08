<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:01:02
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\json\EntityValidator;
use dicr\sberpay\SberPayEntity;

/**
 * Аттрибуты товара.
 */
class ItemAttribute extends SberPayEntity
{
    /** полная предварительная оплата до момента передачи предмета расчёта */
    public const PAYMENT_FULL_BEFORE = 1;

    /** частичная предварительная оплата до момента передачи предмета расчёта */
    public const PAYMENT_PART_BEFORE = 2;

    /** аванс */
    public const PAYMENT_PREPAY = 3;

    /** полная оплата в момент передачи предмета расчёта */
    public const PAYMENT_FULL_AFTER = 4;

    /** частичная оплата предмета расчёта в момент его передачи с последующей оплатой в кредит */
    public const PAYMENT_PART_AFTER = 5;

    /** передача предмета расчёта без его оплаты в момент его передачи с последующей оплатой в кредит */
    public const PAYMENT_NONE_CREDIT = 6;

    /** оплата предмета расчёта после его передачи с оплатой в кредит */
    public const PAYMENT_AFTER_CREDIT = 7;

    /** товар */
    public const OBJECT_PRODUCT = 1;

    /** подакцизный товар */
    public const OBJECT_EXCISABLE_PRODUCT = 2;

    /** работа */
    public const OBJECT_WORK = 3;

    /** услуга */
    public const OBJECT_SERVICE = 4;

    /** ставка азартной игры */
    public const OBJECT_GAMBLING_RATE = 5;

    /** выигрыш азартной игры */
    public const OBJECT_GAMBLING_WIN = 6;

    /** лотерейный билет */
    public const OBJECT_LOTTERY_TICKET = 7;

    /** выигрыш лотереи */
    public const OBJECT_LOTTERY_WIN = 8;

    /** предоставление РИД */
    public const OBJECT_PROVISION_RIA = 9;

    /** платеж */
    public const OBJECT_PAYMENT = 10;

    /** агентское вознаграждение */
    public const OBJECT_AGENT_COMMISSION = 11;

    /** составной предмет расчёта */
    public const OBJECT_COMPOUND = 12;

    /** иной предмет расчёта */
    public const OBJECT_OTHER = 13;

    /** имущественное право */
    public const OBJECT_PROPERTY = 14;

    /** внереализационный доход */
    public const OBJECT_NON_OPERATING = 15;

    /** страховые взносы */
    public const OBJECT_INSURANCE = 16;

    /** торговый сбор */
    public const OBJECT_TRADE = 17;

    /** курортный сбор */
    public const OBJECT_RESORT = 18;

    /**
     * Тип оплаты (PAYMENT_*)
     * Значением по умолчанию является 1 (полная предварительная оплата до момента передачи предмета расчета).
     */
    public ?int $paymentMethod = null;

    /** Тип оплачиваемой позиции. Значением по умолчанию является 1 (товар) */
    public ?int $paymentObject = null;

    /** Код товарной позиции в текстовом представлении. Максимальная длина – 32 байта. */
    public ?string $nomenclature = null;

    /** Значение реквизита пользователя. Можно передавать только после согласования с ФНС. */
    public ?string $userData = null;

    /** агент */
    public AgentInfo|array|null $agentInfo = null;

    /** поставщик */
    public SupplierInfo|array|null $supplierInfo = null;

    /**
     * @inheritDoc
     */
    public function attributeFields(): array
    {
        return [
            'agentInfo' => 'agent_info',
            'supplierInfo' => 'supplier_info'
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeEntities(): array
    {
        return [
            'agentInfo' => AgentInfo::class,
            'supplierInfo' => SupplierInfo::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
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
