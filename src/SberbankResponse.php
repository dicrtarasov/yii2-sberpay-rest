<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:20:30
 */

declare(strict_types = 1);
namespace dicr\sberbank;

/**
 * Ответ Сбербанк.
 */
abstract class SberbankResponse extends SberbankEntity
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
