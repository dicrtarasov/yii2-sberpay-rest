<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 09:56:08
 */

declare(strict_types = 1);
namespace dicr\sberbank;

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
 * @todo реализовать callback https://securepayments.sberbank.ru/wiki/doku.php/integration:api:callback:start
 */
class SberbankModule extends Module
{
    /**
     * @var string URL API для тестов
     * @link https://securepayments.sberbank.ru/wiki/doku.php/test_cards тестовые карты
     */
    public const URL_TEST = 'https://3dsec.sberbank.ru';

    /** @var string URL API */
    public const URL_API = 'https://securepayments.sberbank.ru';

    /** @var string API URL */
    public $url = self::URL_API;

    /** @var ?string открытый токен (может использоваться вместо логина и пароля) */
    public $token;

    /** @var ?string логин (*-api) */
    public $userName;

    /** @var ?string пароль */
    public $password;

    /** @var array конфиг HTTP-клиента */
    public $httpClientConfig = [];

    /** @inheritDoc */
    public $controllerNamespace = __NAMESPACE__;

    /**
     * @inheritDoc
     * @throws InvalidConfigException
     */
    public function init() : void
    {
        parent::init();

        if (empty($this->url)) {
            throw new InvalidConfigException('url');
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
    public function getHttpClient() : Client
    {
        if ($this->_httpClient === null) {
            $this->_httpClient = Yii::createObject(array_merge([
                'class' => Client::class,
            ]));
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
    public function registerPaymentRequest(array $config = []) : RegisterPaymentRequest
    {
        return new RegisterPaymentRequest($this, $config);
    }

    /**
     * Запрос состояния платежа.
     *
     * @param array $config
     * @return OrderStatusRequest
     */
    public function orderStatusRequest(array $config = []) : OrderStatusRequest
    {
        return new OrderStatusRequest($this, $config);
    }
}
