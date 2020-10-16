<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 09:48:12
 */

declare(strict_types = 1);
namespace dicr\sberbank;

use dicr\validate\ValidateException;
use Yii;
use yii\base\Exception;
use yii\httpclient\Client;

/**
 * Запрос состояния платежа.
 */
class OrderStatusRequest extends SberbankRequest
{
    /**
     * @var ?string Номер заказа в платежной системе.
     * В запросе должен присутствовать либо orderId, либо orderNumber. Если в запросе присутствуют оба параметра,
     * то приоритетным считается orderId.
     */
    public $orderId;

    /**
     * @var ?string Номер заказа в системе магазина.
     * В запросе должен присутствовать либо orderId, либо orderNumber. Если в запросе присутствуют оба параметра,
     * то приоритетным считается orderId.
     */
    public $orderNumber;

    /**
     * @var ?string Язык в кодировке ISO 639-1. Если не указан, будет использован язык, указанный в настройках
     * магазина как язык по умолчанию.
     */
    public $language;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return array_merge(parent::rules(), [
            [['orderId', 'orderNumber', 'language'], 'trim'],
            [['orderId', 'orderNumber', 'language'], 'default'],

            ['orderId', 'required', 'when' => function () : bool {
                return empty($this->orderNumber);
            }],

            ['orderNumber', 'required', 'when' => function () : bool {
                return empty($this->orderId);
            }]
        ]);
    }

    /**
     * @inheritDoc
     * @return OrderStatusResponse
     */
    public function send() : OrderStatusResponse
    {
        if (! $this->validate()) {
            throw new ValidateException($this);
        }

        $req = $this->module->httpClient->post('/payment/rest/getOrderStatusExtended.do', $this->json);
        $req->format = Client::FORMAT_JSON;

        Yii::debug('Запрос: ' . $req->toString(), __METHOD__);
        $res = $req->send();
        Yii::debug('Ответ: ' . $res->toString(), __METHOD__);

        if (! $res->isOk) {
            throw new Exception('HTTP-error: ' . $res->statusCode);
        }

        $res->format = Client::FORMAT_JSON;

        $response = new OrderStatusResponse([
            'json' => $res->data
        ]);

        if (! empty($response->errorCode) && $response->errorCode !== 7) {
            throw new Exception($response->errorMessage ?: 'Ошибка: ' . $response->errorCode);
        }

        return $response;
    }
}
