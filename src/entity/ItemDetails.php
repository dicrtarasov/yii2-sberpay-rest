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
 * Дополнительная информация о товаре.
 */
class ItemDetails extends SberpayEntity
{
    /** @var ItemDetailsParam[] */
    public $params;

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
