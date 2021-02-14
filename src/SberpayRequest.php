<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:30:42
 */

declare(strict_types = 1);
namespace dicr\sberpay;

use dicr\validate\ValidateException;
use Yii;
use yii\base\Exception;
use yii\httpclient\Client;

/**
 * Абстрактный запрос.
 */
abstract class SberpayRequest extends SberpayEntity
{
    /** @var SberpayModule */
    protected $module;

    /**
     * Constructor.
     *
     * @param SberpayModule $module
     * @param array $config
     */
    public function __construct(SberpayModule $module, $config = [])
    {
        $this->module = $module;

        parent::__construct($config);
    }

    /**
     * Адрес запроса.
     *
     * @return string
     */
    abstract public static function url(): string;

    /**
     * Отправляет запрос.
     *
     * @return array json data
     * @throws Exception
     * @noinspection PhpMissingReturnTypeInspection
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function send()
    {
        if (! $this->validate()) {
            throw new ValidateException($this);
        }

        $data = array_filter(array_merge([
            'token' => $this->module->token,
            'userName' => $this->module->userName,
            'password' => $this->module->password
        ], $this->json), static fn($val): bool => $val !== null && $val !== '');

        $req = $this->module->httpClient->post(static::url(), $data);

        Yii::debug('Запрос: ' . $req->toString(), __METHOD__);
        $res = $req->send();
        Yii::debug('Ответ: ' . $res->toString(), __METHOD__);

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
