<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 14:14:11
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\json\EntityValidator;
use dicr\sberbank\SberbankEntity;

use function round;

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
    public static function attributeFields() : array
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
    public static function attributeEntities() : array
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

            ['details', 'default'],
            ['details', EntityValidator::class],

            ['quantity', 'required'],
            ['quantity', EntityValidator::class, 'skipOnEmpty' => false],

            ['currency', 'default'],
            ['currency', 'integer', 'min' => 1],
            ['currency', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],

            ['code', 'trim'],
            ['code', 'required'],

            ['discount', 'default'],
            ['discount', EntityValidator::class],

            ['agentInterest', 'default'],
            ['agentInterest', EntityValidator::class],

            ['tax', 'default'],
            ['tax', EntityValidator::class],

            ['price', 'default'],
            ['price', 'integer', 'min' => 0],
            ['price', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],

            // проверяем после quantity и price
            ['amount', 'default', 'value' => function () : ?int {
                return $this->getAmount();
            }],
            ['amount', 'integer', 'min' => 1],
            ['amount', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],

            ['itemAttributes', 'default'],
            ['itemAttributes', EntityValidator::class],
        ];
    }

    /**
     * Рассчитывает сумму.
     *
     * @return ?int
     */
    public function getAmount() : ?int
    {
        return isset($this->price, $this->quantity->value) ?
            (int)round($this->price * $this->quantity->value) : null;
    }
}
