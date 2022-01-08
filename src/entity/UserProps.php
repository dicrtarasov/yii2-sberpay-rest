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
 * Реквизит пользователя.
 */
class UserProps extends SberPayEntity
{
    /** Наименование дополнительного реквизита пользователя. */
    public ?string $name = null;

    /** Значение дополнительного реквизита пользователя. */
    public ?string $value = null;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],

            ['value', 'trim'],
            ['value', 'required']
        ];
    }
}
