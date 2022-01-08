<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:29:48
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\sberpay\PhoneValidator;
use dicr\sberpay\SberPayEntity;
use dicr\validate\InnValidator;

/**
 * Информация о поставщике.
 */
class SupplierInfo extends SberPayEntity
{
    /** Наименование поставщика. */
    public ?string $name = null;

    /** @var string[]|null Массив телефонов поставщика в формате +N. */
    public ?array $phones = null;

    /** ИНН поставщика. */
    public ?int $inn = null;

    /**
     * @inheritDoc
     */
    public function rules(): array
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
