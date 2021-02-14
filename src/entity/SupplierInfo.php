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
use dicr\validate\InnValidator;

/**
 * Информация о поставщике.
 */
class SupplierInfo extends SberpayEntity
{
    /** @var string Наименование поставщика. */
    public $name;

    /** @var string[]|null Массив телефонов поставщика в формате +N. */
    public $phones;

    /** @var ?int ИНН поставщика. */
    public $inn;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['name', 'trim'],
            ['name', 'default'],

            ['phones', 'required'],
            ['phones', 'each', 'rule' => [PhoneValidator::class]],

            ['inn', 'default'],
            ['inn', InnValidator::class]
        ];
    }
}
