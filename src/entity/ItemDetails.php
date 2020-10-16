<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 13:04:58
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\SberbankEntity;
use dicr\validate\EntityValidator;

/**
 * Дополнительная информация о товаре.
 */
class ItemDetails extends SberbankEntity
{
    /** @var ItemDetailsParam[] */
    public $params;

    /**
     * @inheritDoc
     */
    public function attributeFields() : array
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
