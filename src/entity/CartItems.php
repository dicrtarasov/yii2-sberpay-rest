<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 14:14:11
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\json\EntityValidator;
use dicr\sberbank\SberbankEntity;

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
    public static function attributeEntities() : array
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
