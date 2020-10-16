<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:19:31
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\SberbankEntity;

/**
 * Количество товара.
 */
class Quantity extends SberbankEntity
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
