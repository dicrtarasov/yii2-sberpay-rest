<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 10:14:20
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\SberbankEntity;
use dicr\validate\ValidateException;

use function is_array;

/**
 * Товар.
 */
class Item extends SberbankEntity
{
    /**
     * @var int Уникальный идентификатор товарной позиции внутри корзины заказа.
     * Номер позиции в списке: 1, 2, 3 ....
     */
    public $positionId;

    /** @var string Наименование или описание товарной позиции в свободной форме. */
    public $name;

    /** @var ?ItemDetails Дополнительный блок с параметрами описания товарной позиции. */
    public $details;

    /** @var Quantity Элемент описывающий общее количество товарных позиций одного positionId и их меру измерения. */
    public $quantity;

    /**
     * @var ?int Сумма стоимости всех товарных позиций одного positionId в минимальных единицах валюты.
     * itemAmount обязателен к передаче, только если не был передан параметр itemPrice.
     * В противном случае передача itemAmount не требуется.
     * Если же в запросе передаются оба параметра: itemPrice и itemAmount, то itemAmount должен равняться
     * itemPrice * quantity, в противном случае запрос завершится с ошибкой.
     * При расчёте параметра itemAmount = itemPrice*quantity результат округляется до второго знака после
     * десятичного разделителя. Например, если результат вычислений равен 100,255, то итоговый результат
     * будет равен 100,26.
     */
    public $amount;

    /** @var ?int Код валюты товарной позиции ISO 4217. Если не указан, считается равным валюте заказа. */
    public $currency;

    /**
     * @var string Номер (идентификатор) товарной позиции в системе магазина.
     * Параметр должен быть уникальным в рамках запроса.
     */
    public $code;

    /** @var ?Discount Дополнительный блок с атрибутами описания скидки для товарной позиции. */
    public $discount;

    /** @var ?AgentInterest Дополнительный блок с атрибутами описания агентской комиссии за продажу товара. */
    public $agentInterest;

    /**
     * @var ?Tax Дополнительный тег с атрибутами описания налога.
     * Только для магазинов с настройками фискализации.
     */
    public $tax;

    /**
     * @var ?int Стоимость одной товарной позиции в минимальных единицах валюты.
     * Только для магазинов с настройками фискализации.
     * Обязательно для продавцов с фискализацией.
     */
    public $price;

    /**
     * @var ItemAttribute[]|null Блок атрибутов товарной позиции.
     * Только для магазинов с настройками фискализации
     */
    public $itemAttributes;

    /**
     * @inheritDoc
     */
    public function attributeFields() : array
    {
        return [
            'details' => 'itemDetails',
            'amount' => 'itemAmount',
            'currency' => 'itemCurrency',
            'code' => 'itemCode',
            'price' => 'itemPrice'
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeEntities() : array
    {
        return [
            'details' => ItemDetails::class,
            'quantity' => Quantity::class,
            'discount' => Discount::class,
            'agentInterest' => AgentInterest::class,
            'tax' => Tax::class,
            'itemAttributes' => [ItemAttribute::class]
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['positionId', 'required'],
            ['positionId', 'integer', 'min' => 1],
            ['positionId', 'filter', 'filter' => 'intval'],

            ['name', 'trim'],
            ['name', 'required'],

            ['details', function (string $attribute) {
                if (empty($this->details)) {
                    $this->details = null;
                } elseif (! $this->details instanceof ItemDetails) {
                    $this->addError($attribute);
                } elseif (! $this->details->validate()) {
                    $this->addError($attribute, (new ValidateException($this->details))->getMessage());
                }
            }],

            ['quantity', function (string $attribute) {
                if (! $this->quantity instanceof Quantity) {
                    $this->addError($attribute);
                } elseif (! $this->quantity->validate()) {
                    $this->addError($attribute, (new ValidateException($this->quantity))->getMessage());
                }
            }],

            ['amount', 'default', 'value' => $this->price && $this->quantity ?
                (int)round($this->price * $this->quantity->value) : null],
            ['amount', 'integer', 'min' => 1],
            ['amount', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],

            ['currency', 'default'],
            ['currency', 'integer', 'min' => 1],
            ['currency', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],

            ['code', 'trim'],
            ['code', 'required'],

            ['discount', function (string $attribute) {
                if (empty($this->discount)) {
                    $this->discount = null;
                } elseif (! $this->discount instanceof Discount) {
                    $this->addError($attribute);
                } elseif (! $this->discount->validate()) {
                    $this->addError($attribute, (new ValidateException($this->discount))->getMessage());
                }
            }],

            ['agentInterest', function (string $attribute) {
                if (empty($this->agentInterest)) {
                    $this->agentInterest = null;
                } elseif (! $this->agentInterest instanceof AgentInterest) {
                    $this->addError($attribute);
                } elseif (! $this->agentInterest->validate()) {
                    $this->addError($attribute, (new ValidateException($this->agentInterest))->getMessage());
                }
            }],

            ['tax', function (string $attribute) {
                if (empty($this->tax)) {
                    $this->tax = null;
                } elseif (! $this->tax instanceof Tax) {
                    $this->addError($attribute);
                } elseif (! $this->tax->validate()) {
                    $this->addError($attribute, (new ValidateException($this->tax))->getMessage());
                }
            }],

            ['price', 'default'],
            ['price', 'integer', 'min' => 0],
            ['price', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],

            ['itemAttributes', function (string $attribute) {
                if (empty($this->itemAttributes)) {
                    $this->itemAttributes = null;
                } elseif (is_array($this->itemAttributes)) {
                    foreach ($this->itemAttributes as $attr) {
                        if (! $attr instanceof ItemAttribute) {
                            $this->addError($attribute, 'должен быть ItemAttribute');
                        } elseif (! $attr->validate()) {
                            $this->addError($attribute, (new ValidateException($attr))->getMessage());
                        }
                    }
                }
            }]
        ];
    }
}
