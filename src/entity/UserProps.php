<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:20:03
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\SberbankEntity;

/**
 * Реквизит пользователя.
 */
class UserProps extends SberbankEntity
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
