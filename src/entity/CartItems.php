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
use dicr\validate\ValidateException;

use function is_array;

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
            ['items', function (string $attribute) {
                if (is_array($this->items)) {
                    $this->addError($attribute, 'должен быть массив');
                } else {
                    foreach ($this->items as $item) {
                        if (! $this->items instanceof Item) {
                            $this->addError($attribute, 'должен быть Item');
                        } elseif (! $item->validate()) {
                            $this->addError($attribute, (new ValidateException($item))->getMessage());
                        }
                    }
                }
            }]
        ];
    }
}
