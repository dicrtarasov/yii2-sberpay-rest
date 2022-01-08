<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:05:39
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\sberpay\PhoneValidator;
use dicr\sberpay\SberPayEntity;

/**
 * Оператор по приему платежей.
 */
class PaymentsOperator extends SberPayEntity
{
    /** @var string[]|null Массив телефонов оператора по приёму платежей в формате +N. */
    public ?array $phones = null;

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
