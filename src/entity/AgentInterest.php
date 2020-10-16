<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:08:09
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\SberbankEntity;

/**
 * Агентская комиссия.
 */
class AgentInterest extends SberbankEntity
{
    /** @var string Тип агентской комиссии за продажу товара. */
    public $type;

    /** @var int Значение агентской комиссии за продажу товара. */
    public $value;

    /**
     * @inheritDoc
     */
    public function attributeFields() : array
    {
        return [
            'type' => 'interestType',
            'value' => 'interestValue'
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['type', 'trim'],
            ['type', 'required'],

            ['value', 'required'],
            ['value', 'integer', 'min' => 1]
        ];
    }
}
