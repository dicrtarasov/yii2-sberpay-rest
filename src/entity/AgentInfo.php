<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:07:41
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\SberbankEntity;
use dicr\validate\ValidateException;

/**
 * Информация об агенте.
 */
class AgentInfo extends SberbankEntity
{
    /** @var int банковский платёжный агент */
    public const TYPE_BANK_AGENT = 1;

    /** @var int банковский платёжный субагент */
    public const TYPE_BANK_SUB_AGENT = 2;

    /** @var int платёжный агент */
    public const TYPE_PAY_AGENT = 3;

    /** @var int платёжный субагент */
    public const TYPE_PAY_SUB_AGENT = 4;

    /** @var int поверенный */
    public const TYPE_ATTORNEY = 5;

    /** @var int комиссионер */
    public const TYPE_BROKER = 6;

    /** @var int иной агент */
    public const TYPE_OTHER = 7;

    /** @var int Тип агента */
    public $type;

    /** @var ?PayingAgent платежный агент */
    public $paying;

    /** @var ?PaymentsOperator оператор по приему платежей */
    public $paymentsOperator;

    /** @var ?MTOperator оператор перевода */
    public $MTOperator;

    /**
     * @inheritDoc
     */
    public function attributeEntities() : array
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

            ['paying', function (string $attribute) {
                if (empty($this->paying)) {
                    $this->paying = null;
                } elseif (! $this->paying instanceof PayingAgent) {
                    $this->addError($attribute);
                } elseif (! $this->paying->validate()) {
                    $this->addError($attribute, (new ValidateException($this->paying))->getMessage());
                }
            }],

            ['paymentsOperator', 'default'],
            ['paymentsOperator', function (string $attribute) {
                if (empty($this->paymentsOperator)) {
                    $this->paymentsOperator = null;
                } elseif (! $this->paymentsOperator instanceof PaymentsOperator) {
                    $this->addError($attribute);
                } elseif (! $this->paymentsOperator->validate()) {
                    $this->addError($attribute, (new ValidateException($this->paymentsOperator))->getMessage());
                }
            }],

            ['MTOperator', 'default'],
            ['MTOperator', function (string $attribute) {
                if (empty($this->MTOperator)) {
                    $this->MTOperator = null;
                } elseif (! $this->MTOperator instanceof MTOperator) {
                    $this->addError($attribute);
                } elseif (! $this->MTOperator->validate()) {
                    $this->addError($attribute, (new ValidateException($this->MTOperator))->getMessage());
                }
            }]
        ];
    }
}
