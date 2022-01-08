<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:28:14
 */

declare(strict_types = 1);
namespace dicr\sberpay;

/**
 * Ответ Сбербанк.
 */
abstract class SberPayResponse extends SberPayEntity
{
    /** Код ошибки. Может отсутствовать, если результат не привёл к ошибке. */
    public ?int $errorCode = null;

    /** Описание ошибки на языке, переданном в параметре language в запросе. */
    public ?string $errorMessage = null;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            ['errorCode', 'default'],
            ['errorCode', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],

            ['errorMessage', 'trim'],
            ['errorMessage', 'default']
        ];
    }
}
