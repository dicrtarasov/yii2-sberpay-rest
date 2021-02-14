<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:44:32
 */

declare(strict_types = 1);
namespace dicr\sberpay;

use dicr\sberpay\entity\ExternalParams;
use dicr\validate\ValidateException;
use Throwable;
use Yii;

/**
 * Ответ регистрации (создания) платежа.
 */
class RegisterPaymentResponse extends SberPayResponse
{
    /**
     * @var ?string Номер заказа в платежной системе. Уникален в пределах системы.
     * Отсутствует если регистрация заказа на удалась по причине ошибки, детализированной в ErrorCode.
     */
    public $orderId;

    /**
     * @var ?string URL-адрес платёжной формы, на который нужно перенаправить браузер клиента.
     * Не возвращается, если регистрация заказа не удалась по причине ошибки, детализированной в errorCode.
     * Для перенаправления пользователя на страницу оплаты через Сбербанк Онлайн добавьте GET-параметр
     * toWeb2App=true к адресу, который передаётся в параметре formUrl.
     */
    public $formUrl;

    /**
     * @var ?ExternalParams Блок пар key (ключ) - value (значение), который возвращается при оплате по
     * схемам app2app и back2app.
     */
    public $externalParams;

    /**
     * @inheritDoc
     */
    public function attributeEntities() : array
    {
        return [
            'externalParams' => ExternalParams::class
        ];
    }

    /**
     * Переадресация на форму оплаты.
     *
     * @throws ValidateException
     */
    public function redirect() : void
    {
        if (empty($this->formUrl)) {
            throw new ValidateException('formUrl');
        }

        try {
            Yii::$app->end(0, Yii::$app->response->redirect($this->formUrl));
        } catch (Throwable $ex) {
            Yii::error($ex, __METHOD__);
            exit;
        }
    }
}
