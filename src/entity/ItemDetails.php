<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:15:24
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\SberbankEntity;
use dicr\validate\ValidateException;

use function is_array;

/**
 * Дополнительная информация о товаре.
 */
class ItemDetails extends SberbankEntity
{
    /** @var ItemDetailsParams[] */
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
            'params' => [ItemDetailsParams::class]
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['params', function (string $attribute) {
                if (empty($this->params)) {
                    $this->params = null;
                } elseif (is_array($this->params)) {
                    foreach ($this->params as $param) {
                        if (! $param instanceof ItemDetailsParams) {
                            $this->addError($attribute, 'должен быть ItemDetailsParams');
                        } elseif (! $param->validate()) {
                            $this->addError($attribute, (new ValidateException($param))->getMessage());
                        }
                    }
                } else {
                    $this->addError($attribute, 'должен быть массивом');
                }
            }]
        ];
    }
}
