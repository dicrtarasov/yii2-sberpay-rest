<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:35:35
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\sberpay\PhoneValidator;
use dicr\sberpay\SberpayEntity;

/**
 * Оператор по приему платежей.
 */
class PaymentsOperator extends SberpayEntity
{
    /** @var string[] Массив телефонов оператора по приёму платежей в формате +N. */
    public $phones;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            ['phones', 'required'],
            ['phones', 'each', 'rule' => [PhoneValidator::class]]
        ];
    }
}
