<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:07:27
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\sberpay\SberPayEntity;

/**
 * Комиссия.
 */
class Tax extends SberPayEntity
{
    /** без НДС */
    public const TYPE_NO_VAT = 0;

    /** НДС по ставке 0% */
    public const TYPE_VAT0 = 1;

    /** НДС чека по ставке 10% */
    public const TYPE_VAT10 = 2;

    /** НДС чека по расчетной ставке 10/110 */
    public const TYPE_VAT9 = 4;

    /** НДС чека по ставке 20% */
    public const TYPE_VAT20 = 6;

    /** НДС чека по расчётной ставке 20/120 */
    public const TYPE_VAT16 = 7;

    /** Ставка НДС (TYPE_*) */
    public ?int $type = null;

    /** Сумма налога, высчитанная продавцом. Указывается в минимальных единицах валюты (копейках). */
    public ?int $sum = null;

    /**
     * @inheritDoc
     */
    public function attributeFields(): array
    {
        return [
            'type' => 'taxType',
            'sum' => 'taxSum'
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
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
