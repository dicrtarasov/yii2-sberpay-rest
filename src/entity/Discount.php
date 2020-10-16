<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:09:25
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\SberbankEntity;

/**
 * Скидка.
 */
class Discount extends SberbankEntity
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
