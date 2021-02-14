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
 * Количество товара.
 */
class Quantity extends SberPayEntity
{
    /**
     * @var float Количество товарных позиций данного positionId. Для указания дробных чисел используйте
     * десятичную точку.
     */
    public $value;

    /** @var string Мера измерения количества товарной позиции. */
    public $measure;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['value', 'required'],
            ['value', 'number', 'min' => 0.001],

            ['measure', 'trim'],
            ['measure', 'required']
        ];
    }
}
