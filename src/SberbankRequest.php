<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:35:08
 */

declare(strict_types = 1);
namespace dicr\sberbank;

use yii\base\Exception;

/**
 * Абстрактный запрос.
 */
abstract class SberbankRequest extends SberbankEntity
{
    /**
     * @var ?string Значение, которое используется для аутентификации продавца при отправке запросов в платёжный шлюз.
     * При передаче этого параметра параметры userName и password передавать не нужно.
     */
    public $token;

    /**
     * @var ?string Логин служебной учётной записи продавца. При передаче логина и пароля для аутентификации
     * в платёжном шлюзе параметр token передавать не нужно.
     */
    public $userName;

    /**
     * @var ?string Пароль служебной учётной записи продавца.
     * При передаче логина и пароля для аутентификации в платёжном шлюзе параметр token передавать не нужно.
     */
    public $password;

    /** @var SberbankModule */
    protected $module;

    /**
     * Constructor.
     *
     * @param SberbankModule $module
     * @param array $config
     */
    public function __construct(SberbankModule $module, $config = [])
    {
        $this->module = $module;

        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['token', 'trim'],
            ['token', 'default', 'value' => $this->module->token],

            ['userName', 'trim'],
            ['userName', 'default', 'value' => $this->module->userName],
            ['userName', 'required', 'when' => function () : bool {
                return empty($this->token);
            }],

            ['password', 'trim'],
            ['password', 'default', 'value' => $this->module->password],
            ['password', 'required', 'when' => function () : bool {
                return empty($this->token);
            }]
        ];
    }

    /**
     * Отправляет запрос.
     *
     * @return SberbankResponse
     * @throws Exception
     * @noinspection PhpMissingReturnTypeInspection, ReturnTypeCanBeDeclaredInspection
     */
    abstract public function send();
}
