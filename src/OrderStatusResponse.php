<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:12:59
 */

declare(strict_types = 1);
namespace dicr\sberpay;

/**
 * Ответ состояния платежа.
 *
 * Реализованы не все поля.
 *
 * @link https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:getorderstatusextended
 */
class OrderStatusResponse extends SberPayResponse
{
    /** заказ зарегистрирован, но не оплачен */
    public const STATUS_REGISTERED = 0;

    /** предавторизованная сумма удержана (для двухстадийных платежей) */
    public const STATUS_PRE_AUTHORIZED = 1;

    /** проведена полная авторизация суммы заказа */
    public const STATUS_AUTHORIZED = 2;

    /** авторизация отменена */
    public const STATUS_AUTH_CANCELED = 3;

    /** по транзакции была проведена операция возврата */
    public const STATUS_RETURNED = 4;

    /** инициирована авторизация через сервер контроля доступа банка-эмитента */
    public const STATUS_AUTH_INIT = 5;

    /** авторизация отклонена */
    public const STATUS_AUTH_DENIED = 6;

    /** Номер заказа в системе магазина. */
    public ?string $orderNumber = null;

    /**
     * состояние заказа в платёжной системе.
     * Отсутствует, если заказ не был найден.
     */
    public ?int $orderStatus = null;

    /**
     * Код ответа процессинга
     *
     * @link https://securepayments.sberbank.ru/wiki/doku.php/integration:api:actioncode
     */
    public ?int $actionCode = null;

    /**
     * Коды ответа - цифровое обозначение результата,
     * к которому привело обращение к системе со стороны пользователя.
     */
    public ?string $actionCodeDescription = null;

    /** Сумма платежа в минимальных единицах валюты. */
    public ?int $amount = null;

    /** Код валюты платежа ISO 4217 */
    public ?int $currency = null;

    /** Дата регистрации заказа в формате UNIX-времени (POSIX-времени). */
    public ?int $date = null;

    /** Описание заказа в свободной форме. */
    public ?string $orderDescription = null;

    /** IP-адрес покупателя. IPv6 поддерживается во всех запросах (до 39 символов). */
    public ?string $ip = null;

    /** Учётный номер авторизации платежа, который присваивается при регистрации платежа. */
    public ?string $authRefNum = null;

    /** Дата и время возврата средств. */
    public ?string $refundedDate = null;

    /** Способ совершения платежа (платёж в с вводом карточных данных, оплата по связке и т. п.). */
    public ?string $paymentWay = null;
}
