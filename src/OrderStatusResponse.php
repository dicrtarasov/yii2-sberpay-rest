<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 09:34:34
 */

declare(strict_types = 1);
namespace dicr\sberbank;

/**
 * Ответ состояния платежа.
 *
 * Реализованы не все поля.
 *
 * @link https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:getorderstatusextended
 */
class OrderStatusResponse extends SberbankResponse
{
    /** @var int заказ зарегистрирован, но не оплачен */
    public const STATUS_REGISTERED = 0;

    /** @var int предавторизованная сумма удержана (для двухстадийных платежей) */
    public const STATUS_PRE_AUTHORIZED = 1;

    /** @var int проведена полная авторизация суммы заказа */
    public const STATUS_AUTHORIZED = 2;

    /** @var int авторизация отменена */
    public const STATUS_AUTH_CANCELED = 3;

    /** @var int по транзакции была проведена операция возврата */
    public const STATUS_RETURNED = 4;

    /** @var int инициирована авторизация через сервер контроля доступа банка-эмитента */
    public const STATUS_AUTH_INIT = 5;

    /** @var int авторизация отклонена */
    public const STATUS_AUTH_DENIED = 6;

    /** @var string Номер заказа в системе магазина. */
    public $orderNumber;

    /**
     * @var ?int состояние заказа в платёжной системе.
     * Отсутствует, если заказ не был найден.
     */
    public $orderStatus;

    /**
     * @var int Код ответа процессинга
     * @link https://securepayments.sberbank.ru/wiki/doku.php/integration:api:actioncode
     */
    public $actionCode;

    /**
     * @var string Коды ответа - цифровое обозначение результата,
     * к которому привело обращение к системе со стороны пользователя.
     */
    public $actionCodeDescription;

    /** @var int Сумма платежа в минимальных единицах валюты. */
    public $amount;

    /** @var ?int Код валюты платежа ISO 4217 */
    public $currency;

    /** @var int Дата регистрации заказа в формате UNIX-времени (POSIX-времени). */
    public $date;

    /** @var ?string Описание заказа в свободной форме. */
    public $orderDescription;

    /** @var string IP-адрес покупателя. IPv6 поддерживается во всех запросах (до 39 символов). */
    public $ip;

    /** @var ?string Учётный номер авторизации платежа, который присваивается при регистрации платежа. */
    public $authRefNum;

    /** @var ?string Дата и время возврата средств. */
    public $refundedDate;

    /** @var ?string Способ совершения платежа (платёж в с вводом карточных данных, оплата по связке и т. п.). */
    public $paymentWay;
}
