<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:44:32
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\sberpay\SberPayEntity;

/**
 * Реквизит пользователя.
 */
class UserProps extends SberPayEntity
{
    /** @var string Наименование дополнительного реквизита пользователя. */
    public $name;

    /** @var string Значение дополнительного реквизита пользователя. */
    public $value;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],

            ['value', 'trim'],
            ['value', 'required']
        ];
    }
}
