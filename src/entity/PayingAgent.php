<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:19:15
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\PhoneValidator;
use dicr\sberbank\SberbankEntity;

/**
 * Платежный агент.
 */
class PayingAgent extends SberbankEntity
{
    /** @var string Наименование операции платёжного агента. */
    public $operation;

    /** @var string[] Массив телефонов платёжного агента в формате +N. */
    public $phones;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['operation', 'trim'],
            ['operation', 'required'],

            ['phones', 'required'],
            ['phones', 'each', 'rule' => [PhoneValidator::class]]
        ];
    }
}
