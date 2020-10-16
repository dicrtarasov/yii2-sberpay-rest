<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:14:18
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\SberbankEntity;

/**
 * Параметры для схемы app2app и back2app.
 */
class ExternalParams extends SberbankEntity
{
    /** @var ?string Уникальный идентификатор заказа, сгенерированный Банком. */
    public $sbolBankInvoiceId;

    /**** Параметры, возвращаемые для схемы app2app ****************************************/

    /** @var ?string Ссылка на приложение Банка для завершения оплаты. */
    public $sbolDeepLink;

    /**** Параметры, возвращаемые для схемы back2app ****************************************/

    /** @var ?bool Атрибут, информирующий о проходящих регламентных работах */
    public $sbolInactive;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['sbolInactive', 'filter', 'filter' => static function ($val) : ?bool {
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
