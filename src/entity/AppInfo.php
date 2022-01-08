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
 * Информация о приложении.
 */
class AppInfo extends SberPayEntity
{
    public const OS_TYPE_IOS = 'ios';

    public const OS_TYPE_ANDROID = 'android';

    /** Тип ОС */
    public ?string $osType = null;

    /** Ссылка на приложение мерчанта для возврата с успешной оплатой. */
    public ?string $deepLink = null;

    /**
     * @inheritDoc
     */
    public function rules(): array
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
