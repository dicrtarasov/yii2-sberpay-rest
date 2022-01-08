<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:29:48
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\json\EntityValidator;
use dicr\sberpay\SberPayEntity;

/**
 * Информация об агенте.
 */
class AgentInfo extends SberPayEntity
{
    /** банковский платёжный агент */
    public const TYPE_BANK_AGENT = 1;

    /** банковский платёжный субагент */
    public const TYPE_BANK_SUB_AGENT = 2;

    /** платёжный агент */
    public const TYPE_PAY_AGENT = 3;

    /** платёжный субагент */
    public const TYPE_PAY_SUB_AGENT = 4;

    /** поверенный */
    public const TYPE_ATTORNEY = 5;

    /** комиссионер */
    public const TYPE_BROKER = 6;

    /** иной агент */
    public const TYPE_OTHER = 7;

    /** Тип агента */
    public ?int $type = null;

    /** платежный агент */
    public PayingAgent|array|null $paying = null;

    /** оператор по приему платежей */
    public PaymentsOperator|array|null $paymentsOperator = null;

    /** оператор перевода */
    public MTOperator|array|null $MTOperator;

    /**
     * @inheritDoc
     */
    public function attributeEntities(): array
    {
        return [
            'paying' => PayingAgent::class,
            'paymentsOperator' => PaymentsOperator::class,
            'MTOperator' => MTOperator::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['type', 'required'],
            ['type', 'in', 'range' => [self::TYPE_BANK_AGENT, self::TYPE_BANK_SUB_AGENT, self::TYPE_PAY_AGENT,
                self::TYPE_PAY_SUB_AGENT, self::TYPE_ATTORNEY, self::TYPE_BROKER, self::TYPE_OTHER]],
            ['type', 'filter', 'filter' => 'intval'],

            [['paying', 'paymentsOperator', 'MTOperator'], 'default'],
            [['paying', 'paymentsOperator', 'MTOperator'], EntityValidator::class]
        ];
    }
}
