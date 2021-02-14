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
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use function call_user_func;

/**
 * Обработчик callback-уведомлений от банка.
 * Реализована проверка только СИММЕТРИЧНОЙ криптографии контрольной суммы.
 *
 * @property-read SberpayModule $module
 */
class CallbackController extends Controller
{
    /**
     * Индекс.
     *
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionIndex(): string
    {
        Yii::debug('Callback: ' . $_SERVER['QUERY_STRING'], __METHOD__);

        $callbackRequest = new CallbackRequest($this->module);
        $callbackRequest->load($this->request->get());
        if (! $callbackRequest->validate()) {
            throw new BadRequestHttpException(
                'Некорректный запрос', 0, new ValidateException($callbackRequest)
            );
        }

        // вызов обработчика пользователя
        if ($this->module->handler !== null) {
            call_user_func($this->module->handler, $callbackRequest);
        }

        return 'Ok';
    }
}
