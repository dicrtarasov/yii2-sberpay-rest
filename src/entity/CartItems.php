<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 17:52:26
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\json\EntityValidator;
use dicr\sberpay\SberPayEntity;

/**
 * Элементы корзины.
 */
class CartItems extends SberPayEntity
{
    /** @var Item[]|array[] */
    public array $items = [];

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
