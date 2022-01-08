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

/**
 * Платежный агент.
 */
class PayingAgent extends SberPayEntity
{
    /** Наименование операции платёжного агента. */
    public ?string $operation = null;

    /** @var string[]|null Массив телефонов платёжного агента в формате +N. */
    public ?array $phones = null;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            ['operation', 'trim'],
            ['operation', 'required'],

            ['phones', 'required'],
            ['phones', 'each', 'rule' => [PhoneValidator::class]]
        ];
    }
}
