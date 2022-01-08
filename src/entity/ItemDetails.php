<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:01:35
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\json\EntityValidator;
use dicr\sberpay\SberPayEntity;

/**
 * Дополнительная информация о товаре.
 */
class ItemDetails extends SberPayEntity
{
    /** @var ItemDetailsParam[]|array[]|null */
    public ?array $params = null;

    /**
     * @inheritDoc
     */
    public function attributeFields(): array
    {
        return [
            'params' => 'itemDetailsParams'
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeEntities() : array
    {
        return [
            'params' => [ItemDetailsParam::class]
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['params', 'required'],
            ['params', EntityValidator::class, 'skipOnEmpty' => false],
        ];
    }
}
