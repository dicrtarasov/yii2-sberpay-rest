<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:30:42
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\sberpay\PhoneValidator;
use dicr\sberpay\SberpayEntity;

/**
 * Платежный агент.
 */
class PayingAgent extends SberpayEntity
{
    /** @var string Наименование операции платёжного агента. */
    public $operation;

    /** @var string[] Массив телефонов платёжного агента в формате +N. */
    public $phones;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['operation', 'trim'],
            ['operation', 'required'],

            ['phones', 'required'],
            ['phones', 'each', 'rule' => [PhoneValidator::class]]
        ];
    }
}
