<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:15:33
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\PhoneValidator;
use dicr\sberbank\SberbankEntity;
use dicr\validate\InnValidator;

/**
 * Оператор перевода.
 */
class MTOperator extends SberbankEntity
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
