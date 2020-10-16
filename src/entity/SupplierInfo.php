<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:19:40
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\PhoneValidator;
use dicr\sberbank\SberbankEntity;
use dicr\validate\InnValidator;

/**
 * Информация о поставщике.
 */
class SupplierInfo extends SberbankEntity
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
