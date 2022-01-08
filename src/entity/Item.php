<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 17:58:30
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\json\EntityValidator;
use dicr\sberpay\SberPayEntity;

use function round;

/**
 * Товар.
 */
class Item extends SberPayEntity
{
    /**
     * Уникальный идентификатор товарной позиции внутри корзины заказа.
     * Номер позиции в списке: 1, 2, 3 ....
     */
    public ?int $positionId = null;

    /** Наименование или описание товарной позиции в свободной форме. */
    public ?string $name = null;

    /** Дополнительный блок с параметрами описания товарной позиции. */
    public ItemDetails|array|null $details = null;

    /** Элемент описывающий общее количество товарных позиций одного positionId и их меру измерения. */
    public Quantity|array|null $quantity = null;

    /**
     * Сумма стоимости всех товарных позиций одного positionId в минимальных единицах валюты.
     * itemAmount обязателен к передаче, только если не был передан параметр itemPrice.
     * В противном случае передача itemAmount не требуется.
     * Если же в запросе передаются оба параметра: itemPrice и itemAmount, то itemAmount должен равняться
     * itemPrice * quantity, в противном случае запрос завершится с ошибкой.
     * При расчёте параметра itemAmount = itemPrice*quantity результат округляется до второго знака после
     * десятичного разделителя. Например, если результат вычислений равен 100,255, то итоговый результат
     * будет равен 100,26.
     */
    public ?int $amount = null;

    /** Код валюты товарной позиции ISO 4217. Если не указан, считается равным валюте заказа. */
    public ?int $currency = null;

    /**
     * Номер (идентификатор) товарной позиции в системе магазина.
     * Параметр должен быть уникальным в рамках запроса.
     */
    public ?string $code = null;

    /** Дополнительный блок с атрибутами описания скидки для товарной позиции. */
    public Discount|array|null $discount = null;

    /** Дополнительный блок с атрибутами описания агентской комиссии за продажу товара. */
    public AgentInterest|array|null $agentInterest = null;

    /**
     * Дополнительный тег с атрибутами описания налога.
     * Только для магазинов с настройками фискализации.
     */
    public Tax|array|null $tax = null;

    /**
     * Стоимость одной товарной позиции в минимальных единицах валюты.
     * Только для магазинов с настройками фискализации.
     * Обязательно для продавцов с фискализацией.
     */
    public ?int $price = null;

    /**
     * @var ItemAttribute[]|array[]|null Блок атрибутов товарной позиции.
     * Только для магазинов с настройками фискализации
     */
    public ?array $itemAttributes = null;

    /**
     * @inheritDoc
     */
    public function attributeFields(): array
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
    public function attributeEntities(): array
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
    public function rules(): array
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
            ['amount', 'default', 'value' => fn(): ?int => $this->amount()],
            ['amount', 'integer', 'min' => 1],
            ['amount', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],

            ['itemAttributes', 'default'],
            ['itemAttributes', EntityValidator::class],
        ];
    }

    /**
     * Рассчитывает сумму.
     */
    public function amount(): ?int
    {
        return isset($this->price, $this->quantity->value) ?
            (int)round($this->price * $this->quantity->value) : null;
    }
}
