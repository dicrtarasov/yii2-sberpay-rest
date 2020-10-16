<?php

/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 12:49:44
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
                ['class' => yii\log\FileTarget::class, 'levels' => ['error', 'warning', 'info', 'trace']]
            ]
        ]
    ],
    'modules' => [
        'sberbank' => [
            'class' => dicr\sberbank\SberbankModule::class,
            'url' => dicr\sberbank\SberbankModule::URL_TEST,
            'userName' => 'ekoyar-api', // тестовый логин
            'password' => 'ekoyar' // тестовый пароль
        ]
    ]
]);
