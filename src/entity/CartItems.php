<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 12:58:43
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\SberbankEntity;
use dicr\validate\EntityValidator;

/**
 * Элементы корзины.
 */
class CartItems extends SberbankEntity
{
    /** @var Item[] */
    public $items;

    /**
     * @inheritDoc
     */
    public function attributeEntities() : array
    {
        return [
            'items' => [Item::class]
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['items', EntityValidator::class, 'skipOnEmpty' => false]
        ];
    }
}
