<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 16.10.20 08:18:27
 */

declare(strict_types = 1);
namespace dicr\sberbank\entity;

use dicr\sberbank\SberbankEntity;
use dicr\validate\ValidateException;

use function is_array;

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
            ['agentInfo', function (string $attribute) {
                if (empty($this->agentInfo)) {
                    $this->agentInfo = null;
                } elseif (! $this->agentInfo instanceof AgentInfo) {
                    $this->addError($attribute);
                } elseif (! $this->agentInfo->validate()) {
                    $this->addError($attribute, (new ValidateException($this->agentInfo))->getMessage());
                }
            }],

            ['supplierInfo', function (string $attribute) {
                if (empty($this->supplierInfo)) {
                    $this->supplierInfo = null;
                } elseif (! $this->supplierInfo instanceof SupplierInfo) {
                    $this->addError($attribute);
                } elseif (! $this->supplierInfo->validate()) {
                    $this->addError($attribute, (new ValidateException($this->supplierInfo))->getMessage());
                }
            }],

            ['cashier', 'trim'],
            ['cashier', 'default'],

            ['additionalCheckProps', 'trim'],
            ['additionalCheckProps', 'default'],

            ['additionalUserProps', function (string $attribute) {
                if (empty($this->additionalUserProps)) {
                    $this->additionalUserProps = null;
                } elseif (is_array($this->additionalUserProps)) {
                    foreach ($this->additionalUserProps as $prop) {
                        if (! $prop instanceof UserProps) {
                            $this->addError($attribute, 'требуется UserProps');
                        } elseif (! $prop->validate()) {
                            $this->addError($attribute, (new ValidateException($prop))->getMessage());
                        }
                    }
                } else {
                    $this->addError($attribute, 'требуется массив');
                }
            }]
        ];
    }
}
