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
 * Количество товара.
 */
class Quantity extends SberPayEntity
{
    /**
     * Количество товарных позиций данного positionId. Для указания дробных чисел используйте
     * десятичную точку.
     */
    public ?float $value = null;

    /** Мера измерения количества товарной позиции. */
    public ?string $measure = null;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            ['value', 'required'],
            ['value', 'number', 'min' => 0.001],

            ['measure', 'trim'],
            ['measure', 'required']
        ];
    }
}
