<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:27:44
 */

declare(strict_types = 1);
namespace dicr\sberpay;

use dicr\helper\Log;
use dicr\validate\ValidateException;
use yii\base\Exception;
use yii\httpclient\Client;

/**
 * Абстрактный запрос.
 */
abstract class SberPayRequest extends SberPayEntity
{
    /**
     * Constructor.
     */
    public function __construct(
        protected SberPayModule $module,
        array $config = []
    ) {
        parent::__construct($config);
    }

    /**
     * Адрес запроса.
     */
    abstract public function url(): string;

    /**
     * Отправляет запрос.
     *
     * @throws Exception
     */
    public function send(): mixed
    {
        if (! $this->validate()) {
            throw new ValidateException($this);
        }

        $data = $this->json;
        if (! empty($this->module->token)) {
            $data['token'] = $this->module->token;
        } else {
            $data['userName'] = $this->module->userName;
            $data['password'] = $this->module->password;
        }

        $data = array_filter(
            $data,
            static fn($val): bool => $val !== null && $val !== ''
        );

        $req = $this->module->httpClient->post(static::url(), $data);
        Log::debug('Запрос: ' . $req->toString());

        $res = $req->send();
        Log::debug('Ответ: ' . $res->toString());

        if (! $res->isOk) {
            throw new Exception('HTTP-error: ' . $res->statusCode);
        }

        $res->format = Client::FORMAT_JSON;
        if (! empty($res->data['errorCode'])) {
            throw new Exception($res->data['errorMessage'] ?: ('Ошибка: ' . $res->data['errorCode']));
        }

        return $res->data;
    }
}
