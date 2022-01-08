<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:03:36
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
    /** Наименование оператора перевода. */
    public ?string $name = null;

    /** @var string[]|null Массив телефонов оператора перевода в формате +N. */
    public ?array $phones = null;

    /** Адрес оператора перевода. */
    public ?string $address = null;

    /** ИНН оператора перевода. */
    public ?int $inn = null;

    /**
     * @inheritDoc
     */
    public function rules(): array
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
