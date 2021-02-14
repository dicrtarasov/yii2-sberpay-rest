<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 08:18:08
 */

declare(strict_types = 1);
namespace dicr\sberpay;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\httpclient\Client;

use function array_merge;
use function is_callable;

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
     * @var string URL API для тестов
     * @link https://securepayments.sberbank.ru/wiki/doku.php/test_cards тестовые карты
     */
    public const URL_TEST = 'https://3dsec.sberbank.ru/payment/rest';

    /** @var string URL API */
    public const URL_API = 'https://securepayments.sberbank.ru/payment/rest';

    /** @var string API URL */
    public $url = self::URL_API;

    /** @var ?string открытый токен (может использоваться вместо логина и пароля) */
    public $token;

    /** @var ?string логин (*-api) */
    public $userName;

    /** @var ?string пароль */
    public $password;

    /**
     * @var ?string секретный ключ для проверки callback-уведомлений.
     * Если не установлен, то checksum в callback-запросе не проверяются.
     * Реализована только проверка контрольной суммы с использованием СИММЕТРИЧНОЙ криптографии.
     */
    public $secureToken;

    /** @var ?callable function(CallbackRequest $request): void обработчик callback-запросов */
    public $handler;

    /** @var array конфиг HTTP-клиента */
    public $httpClientConfig = [];

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

        if ($this->handler !== null && ! is_callable($this->handler)) {
            throw new InvalidConfigException('handler');
        }
    }

    /** @var Client */
    private $_httpClient;

    /**
     * HTTP-клиент.
     *
     * @return Client
     * @throws InvalidConfigException
     */
    public function getHttpClient(): Client
    {
        if ($this->_httpClient === null) {
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
     *
     * @param array $config
     * @return RegisterPaymentRequest
     */
    public function registerPaymentRequest(array $config = []): RegisterPaymentRequest
    {
        return new RegisterPaymentRequest($this, $config);
    }

    /**
     * Запрос состояния платежа.
     *
     * @param array $config
     * @return OrderStatusRequest
     */
    public function orderStatusRequest(array $config = []): OrderStatusRequest
    {
        return new OrderStatusRequest($this, $config);
    }
}
