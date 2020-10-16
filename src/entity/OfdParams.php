<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 13:06:40
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\SberbankEntity;
use dicr\validate\EntityValidator;

/**
 * Дополнительные параметры ОФД.
 */
class OfdParams extends SberbankEntity
{
    /** @var ?AgentInfo агент */
    public $agentInfo;

    /** @var ?SupplierInfo поставщик */
    public $supplierInfo;

    /** @var ?string ФИО кассира. */
    public $cashier;

    /** @var ?string Дополнительный реквизит чека. */
    public $additionalCheckProps;

    /** @var UserProps[]|null */
    public $additionalUserProps;

    /**
     * @inheritDoc
     */
    public function attributeFields() : array
    {
        return [
            'agentInfo' => 'agent_info',
            'supplierInfo' => 'supplier_info',
            'additionalCheckProps' => 'additional_check_props',
            'additionalUserProps' => 'additional_user_props'
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeEntities() : array
    {
        return [
            'agentInfo' => AgentInfo::class,
            'supplierInfo' => SupplierInfo::class,
            'additionalUserProps' => [UserProps::class]
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['agentInfo', 'default'],
            ['agentInfo', EntityValidator::class],

            ['supplierInfo', 'default'],
            ['supplierInfo', EntityValidator::class],

            ['cashier', 'trim'],
            ['cashier', 'default'],

            ['additionalCheckProps', 'trim'],
            ['additionalCheckProps', 'default'],

            ['additionalUserProps', 'default'],
            ['additionalUserProps', EntityValidator::class],
        ];
    }
}
