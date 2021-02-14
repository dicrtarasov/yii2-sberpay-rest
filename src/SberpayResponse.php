<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:30:42
 */

declare(strict_types = 1);
namespace dicr\sberpay;

/**
 * Ответ Сбербанк.
 */
abstract class SberpayResponse extends SberpayEntity
{
    /** @var ?int Код ошибки. Может отсутствовать, если результат не привёл к ошибке. */
    public $errorCode;

    /** @var ?string Описание ошибки на языке, переданном в параметре language в запросе. */
    public $errorMessage;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['errorCode', 'default'],
            ['errorCode', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],

            ['errorMessage', 'trim'],
            ['errorMessage', 'default']
        ];
    }
}
