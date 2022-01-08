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
 * Дополнительная информация о товаре.
 */
class ItemDetailsParam extends SberPayEntity
{
    /** Наименование параметра описания детализации товарной позиции. */
    public ?string $name = null;

    /** Дополнительная информация по товарной позиции. */
    public ?string $value = null;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],

            ['value', 'trim'],
            ['value', 'required']
        ];
    }
}
