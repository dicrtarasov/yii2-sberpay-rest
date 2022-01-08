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
 * Скидка.
 */
class Discount extends SberPayEntity
{
    /** Тип скидки на товарную позицию. */
    public ?string $type = null;

    /** Значение скидки на товарную позицию. */
    public ?int $value = null;

    /**
     * @inheritDoc
     */
    public function attributeFields(): array
    {
        return [
            'type' => 'discountType',
            'value' => 'discountValue'
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['type', 'trim'],
            ['type', 'required'],

            ['value', 'required'],
            ['value', 'integer', 'min' => 1]
        ];
    }
}
