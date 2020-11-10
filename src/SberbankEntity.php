<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 10.11.20 17:20:02
 */

declare(strict_types = 1);
namespace dicr\sberbank;

use dicr\json\JsonEntity;

/**
 * Абстрактная структура данных Сбербанк.
 */
abstract class SberbankEntity extends JsonEntity
{
    /**
     * @inheritDoc
     */
    public function attributeFields() : array
    {
        // по-умолчанию отключаем трансляцию названий аттрибутов
        return [];
    }
}
