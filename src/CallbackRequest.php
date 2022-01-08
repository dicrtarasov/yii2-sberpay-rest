<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:09:30
 */

declare(strict_types = 1);
namespace dicr\sberpay;

use function hash_hmac;
use function ksort;
use function strtoupper;

/**
 * Уведомление о статусе платежа.
 * Реализована только проверка контрольной суммы с использованием СИММЕТРИЧНОЙ криптографии.
 *
 * @link https://securepayments.sberbank.ru/wiki/doku.php/integration:api:callback:start
 */
class CallbackRequest extends SberPayEntity
{
    /** операция удержания (холдирования) суммы */
    public const OPERATION_APPROVED = 'approved';

    /** операция отклонения заказа по истечении его времени жизни */
    public const OPERATION_TIMEOUT = 'declinedByTimeout';

    /** операция завершения */
    public const OPERATION_DEPOSITED = 'deposited';

    /** операция отмены */
    public const OPERATION_REVERSED = 'reversed';

    /** операция возврата */
    public const OPERATION_REFUNDED = 'refunded';

    /** Уникальный номер заказа в системе платёжного шлюза. */
    public ?string $mdOrder = null;

    /** Уникальный номер (идентификатор) заказа в системе продавца. */
    public ?string $orderNumber = null;

    /** Тип операции, о которой пришло уведомление */
    public ?string $operation = null;

    /** Индикатор успешности операции, указанной в параметре operation */
    public ?bool $status = null;

    /** Аутентификационный код, или контрольная сумма, полученная из набора параметров. */
    public ?string $checksum = null;

    /**
     * CallbackRequest constructor.
     */
    public function __construct(
        private SberPayModule $module,
        array $config = []
    ) {
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['mdOrder', 'orderNumber'], 'required'],

            ['operation', 'required'],

            ['status', 'required'],
            ['status', 'boolean'],
            ['status', 'filter', 'filter' => 'boolval'],

            ['checksum', 'trim'],
            ['checksum', 'required', 'when' => fn(): bool => ! empty($this->module->secureToken)],
            ['checksum', function (string $attribute): void {
                // параметры запроса
                $get = $_GET;

                // выделяем checksum
                unset($get['checksum']);

                // сортируем параметры
                ksort($get);

                // формируем строку
                $str = '';
                foreach ($get as $key => $val) {
                    $str .= $key . ';' . $val . ';';
                }

                // рассчитываем контрольную сумму
                $hmac = strtoupper(hash_hmac('sha256', $str, (string)$this->module->secureToken));
                if ($hmac !== $this->{$attribute}) {
                    $this->addError($attribute, 'некорректный проверочный код');
                }
            }]
        ];
    }
}
