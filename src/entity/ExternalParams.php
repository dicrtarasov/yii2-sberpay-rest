<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:29:48
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\sberpay\SberPayEntity;

/**
 * Параметры для схемы app2app и back2app.
 * Поле externalParams в ответе RegisterPayment
 *
 * @link https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:register
 */
class ExternalParams extends SberPayEntity
{
    /** Уникальный идентификатор заказа, сгенерированный Банком. */
    public ?string $sbolBankInvoiceId = null;

    /**** Параметры, возвращаемые для схемы app2app ****************************************/

    /** Ссылка на приложение Банка для завершения оплаты. */
    public ?string $sbolDeepLink = null;

    /**** Параметры, возвращаемые для схемы back2app ****************************************/

    /** Атрибут, информирующий о проходящих регламентных работах */
    public ?bool $sbolInactive = null;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            ['sbolInactive', 'filter', 'filter' => static function ($val): ?bool {
                if ($val === null || $val === '') {
                    return null;
                }

                if ($val === 'true') {
                    return true;
                }

                if ($val === 'false') {
                    return false;
                }

                return (bool)$val;
            }]
        ];
    }
}
