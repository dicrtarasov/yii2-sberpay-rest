<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:30:42
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\sberpay\SberpayEntity;

/**
 * Агентская комиссия.
 */
class AgentInterest extends SberpayEntity
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
