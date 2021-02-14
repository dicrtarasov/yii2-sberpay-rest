<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:44:44
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
    /** @var string операция удержания (холдирования) суммы */
    public const OPERATION_APPROVED = 'approved';

    /** @var string операция отклонения заказа по истечении его времени жизни */
    public const OPERATION_TIMEOUT = 'declinedByTimeout';

    /** @var string операция завершения */
    public const OPERATION_DEPOSITED = 'deposited';

    /** @var string операция отмены */
    public const OPERATION_REVERSED = 'reversed';

    /** @var string операция возврата */
    public const OPERATION_REFUNDED = 'refunded';

    /** @var string Уникальный номер заказа в системе платёжного шлюза. */
    public $mdOrder;

    /** @var string Уникальный номер (идентификатор) заказа в системе продавца. */
    public $orderNumber;

    /** @var string Тип операции, о которой пришло уведомление */
    public $operation;

    /** @var bool Индикатор успешности операции, указанной в параметре operation */
    public $status;

    /** @var ?string Аутентификационный код, или контрольная сумма, полученная из набора параметров. */
    public $checksum;

    /** @var SberPayModule */
    private $module;

    /**
     * CallbackRequest constructor.
     *
     * @param SberPayModule $module
     * @param array $config
     */
    public function __construct(SberPayModule $module, array $config = [])
    {
        $this->module = $module;

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
            ['checksum', 'required', 'when' => fn(): bool => ! empty($this->module->secretToken)],
            ['checksum', function(string $attribute): void {
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
                $hmac = strtoupper(hash_hmac('sha256', $str, (string)$this->module->secretToken));
                if ($hmac !== $this->{$attribute}) {
                    $this->addError($attribute, 'некорректный проверочный код');
                }
            }]
        ];
    }
}

