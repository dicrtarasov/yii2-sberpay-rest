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
 * Дополнительная информация о товаре.
 */
class ItemDetails extends SberbankEntity
{
    /** @var ItemDetailsParam[] */
    public $params;

    /**
     * @inheritDoc
     */
    public static function attributeFields() : array
    {
        return [
            'params' => 'itemDetailsParams'
        ];
    }

    /**
     * @inheritDoc
     */
    public static function attributeEntities() : array
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
