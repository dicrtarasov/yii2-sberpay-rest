<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:03:53
 */

declare(strict_types = 1);
namespace dicr\sberpay\entity;

use dicr\json\EntityValidator;
use dicr\sberpay\SberPayEntity;

/**
 * Дополнительные параметры ОФД.
 */
class OfdParams extends SberPayEntity
{
    /** агент */
    public AgentInfo|array|null $agentInfo = null;

    /** поставщик */
    public SupplierInfo|array|null $supplierInfo = null;

    /** ФИО кассира. */
    public ?string $cashier = null;

    /** Дополнительный реквизит чека. */
    public ?string $additionalCheckProps = null;

    /** @var UserProps[]|array[]|null */
    public ?array $additionalUserProps = null;

    /**
     * @inheritDoc
     */
    public function attributeFields(): array
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
    public function attributeEntities(): array
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
    public function rules(): array
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
