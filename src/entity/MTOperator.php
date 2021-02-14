<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:44:32
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\sberpay\PhoneValidator;
use dicr\sberpay\SberPayEntity;
use dicr\validate\InnValidator;

/**
 * Оператор перевода.
 */
class MTOperator extends SberPayEntity
{
    /** @var string Наименование оператора перевода. */
    public $name;

    /** @var string[]|null Массив телефонов оператора перевода в формате +N. */
    public $phones;

    /** @var ?string Адрес оператора перевода. */
    public $address;

    /** @var ?int ИНН оператора перевода. */
    public $inn;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],

            ['phones', 'default'],
            ['phones', 'each', 'rule' => [PhoneValidator::class]],

            ['address', 'trim'],
            ['address', 'default'],

            ['inn', 'default'],
            ['inn', InnValidator::class, 'skipOnEmpty' => true]
        ];
    }
}
