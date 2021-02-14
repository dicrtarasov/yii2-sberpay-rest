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
 * Скидка.
 */
class Discount extends SberPayEntity
{
    /** @var string Тип скидки на товарную позицию. */
    public $type;

    /** @var int Значение скидки на товарную позицию. */
    public $value;

    /**
     * @inheritDoc
     */
    public function attributeFields() : array
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
