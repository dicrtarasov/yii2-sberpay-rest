<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:27:02
 */

declare(strict_types = 1);
namespace dicr\sberpay;

use Closure;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\httpclient\Client;

use function array_merge;

/**
 * Модуль клиента Сбербанк.
 *
 * @property-read Client $httpClient
 *
 * @link https://securepayments.sberbank.ru/wiki/doku.php/main_page API
 */
class SberPayModule extends Module
{
    /**
     * URL API для тестов
     *
     * @link https://securepayments.sberbank.ru/wiki/doku.php/test_cards тестовые карты
     */
    public const URL_TEST = 'https://3dsec.sberbank.ru/payment/rest';

    /** URL API */
    public const URL_API = 'https://securepayments.sberbank.ru/payment/rest';

    /** API URL */
    public string $url = self::URL_API;

    /** открытый токен (может использоваться вместо логина и пароля) */
    public ?string $token = null;

    /** логин (*-api) */
    public ?string $userName = null;

    /** пароль */
    public ?string $password = null;

    /**
     * секретный ключ для проверки callback-уведомлений.
     * Если не установлен, то checksum в callback-запросе не проверяются.
     * Реализована только проверка контрольной суммы с использованием СИММЕТРИЧНОЙ криптографии.
     */
    public ?string $secureToken = null;

    /** function(CallbackRequest $request): void обработчик callback-запросов */
    public ?Closure $handler = null;

    /** конфиг HTTP-клиента */
    public array $httpClientConfig = [];

    /** @inheritDoc */
    public $controllerNamespace = __NAMESPACE__;

    /**
     * @inheritDoc
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        if (empty($this->url)) {
            throw new InvalidConfigException('url');
        }

        if (empty($this->token)) {
            if (empty($this->userName)) {
                throw new InvalidConfigException('userName');
            }

            if (empty($this->password)) {
                throw new InvalidConfigException('password');
            }
        }
    }

    private Client $_httpClient;

    /**
     * HTTP-клиент.
     *
     * @throws InvalidConfigException
     */
    public function getHttpClient(): Client
    {
        if (! isset($this->_httpClient)) {
            /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
            $this->_httpClient = Yii::createObject(array_merge([
                'class' => Client::class,
            ], $this->httpClientConfig ?: []));
        }

        // динамически обновляем baseUrl
        $this->_httpClient->baseUrl = $this->url;

        return $this->_httpClient;
    }

    /**
     * Запрос на создание платежа.
     */
    public function registerPaymentRequest(array $config = []): RegisterPaymentRequest
    {
        return new RegisterPaymentRequest($this, $config);
    }

    /**
     * Запрос состояния платежа.
     */
    public function orderStatusRequest(array $config = []): OrderStatusRequest
    {
        return new OrderStatusRequest($this, $config);
    }
}
