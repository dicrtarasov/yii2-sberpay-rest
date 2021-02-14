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
 * Комиссия.
 */
class Tax extends SberPayEntity
{
    /** @var int без НДС */
    public const TYPE_NO_VAT = 0;

    /** @var int НДС по ставке 0% */
    public const TYPE_VAT0 = 1;

    /** @var int  НДС чека по ставке 10% */
    public const TYPE_VAT10 = 2;

    /** @var int НДС чека по расчетной ставке 10/110 */
    public const TYPE_VAT9 = 4;

    /** @var int НДС чека по ставке 20% */
    public const TYPE_VAT20 = 6;

    /** @var int НДС чека по расчётной ставке 20/120 */
    public const TYPE_VAT16 = 7;

    /** @var ?int Ставка НДС (TYPE_*) */
    public $type;

    /** @var ?int Сумма налога, высчитанная продавцом. Указывается в минимальных единицах валюты (копейках). */
    public $sum;

    /**
     * @inheritDoc
     */
    public function attributeFields() : array
    {
        return [
            'type' => 'taxType',
            'sum' => 'taxSum'
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['type', 'default'],
            ['type', 'in', 'range' => [self::TYPE_NO_VAT, self::TYPE_VAT0, self::TYPE_VAT10, self::TYPE_VAT9,
                self::TYPE_VAT20, self::TYPE_VAT16]],
            ['type', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],

            ['sum', 'default'],
            ['sum', 'integer', 'min' => 1],
            ['sum', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true]
        ];
    }
}
