<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:35:35
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\json\EntityValidator;
use dicr\sberpay\SberpayEntity;

/**
 * Элементы корзины.
 */
class CartItems extends SberpayEntity
{
    /** @var Item[] */
    public $items;

    /**
     * @inheritDoc
     */
    public function attributeEntities(): array
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
