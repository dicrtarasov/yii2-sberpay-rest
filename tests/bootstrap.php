<?php

/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:44:44
 */
declare(strict_types = 1);

/** среда разработки */
defined('YII_ENV') || define('YII_ENV', 'dev');

/** режим отладки */
defined('YII_DEBUG') || define('YII_DEBUG', true);

require_once(dirname(__DIR__) . '/vendor/autoload.php');
require_once(dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php');

/** @noinspection PhpUnhandledExceptionInspection */
new yii\console\Application([
    'id' => 'test',
    'basePath' => dirname(__DIR__),
    'components' => [
        'urlManager' => [
            'hostInfo' => 'https://localhost'
        ],
        'log' => [
            'targets' => [
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => ['error', 'warning', 'info', 'trace']
                ]
            ]
        ]
    ],
    'modules' => [
        'sberpay' => [
            'class' => dicr\sberpay\SberPayModule::class,
            'url' => dicr\sberpay\SberPayModule::URL_TEST,
            'userName' => 'test-api', // тестовый логин
            'password' => 'test' // тестовый пароль
        ]
    ],
    'bootstrap' => ['log']
]);
