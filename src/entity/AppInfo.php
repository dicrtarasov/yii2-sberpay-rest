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
 * Информация о приложении.
 */
class AppInfo extends SberPayEntity
{
    /** @var string */
    public const OS_TYPE_IOS = 'ios';

    /** @var string */
    public const OS_TYPE_ANDROID = 'android';

    /** @var string Тип ОС */
    public $osType;

    /** @var string Ссылка на приложение мерчанта для возврата с успешной оплатой. */
    public $deepLink;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['osType', 'required'],
            ['osType', 'in', 'range' => [self::OS_TYPE_IOS, self::OS_TYPE_ANDROID]],

            ['deepLink', 'trim'],
            ['deepLink', 'required'],
            ['deepLink', 'url']
        ];
    }
}
