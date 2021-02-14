<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 14.02.21 06:30:42
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\json\EntityValidator;
use dicr\sberpay\SberpayEntity;

/**
 * Дополнительные параметры ОФД.
 */
class OfdParams extends SberpayEntity
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
