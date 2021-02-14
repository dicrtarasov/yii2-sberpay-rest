<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:30:42
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\sberpay\SberpayEntity;

/**
 * Дополнительная информация о товаре.
 */
class ItemDetailsParam extends SberpayEntity
{
    /** @var string Наименование параметра описания детализации товарной позиции. */
    public $name;

    /** @var string Дополнительная информация по товарной позиции. */
    public $value;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],

            ['value', 'trim'],
            ['value', 'required']
        ];
    }
}
