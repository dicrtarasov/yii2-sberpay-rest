<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:19:23
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\PhoneValidator;
use dicr\sberbank\SberbankEntity;

/**
 * Оператор по приему платежей.
 */
class PaymentsOperator extends SberbankEntity
{
    /** @var string[] Массив телефонов оператора по приёму платежей в формате +N. */
    public $phones;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['phones', 'required'],
            ['phones', 'each', 'rule' => [PhoneValidator::class]]
        ];
    }
}
