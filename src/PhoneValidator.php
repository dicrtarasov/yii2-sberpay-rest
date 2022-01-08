<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:13:32
 */

declare(strict_types = 1);
namespace dicr\sberpay;

use dicr\validate\AbstractValidator;
use dicr\validate\ValidateException;

use function preg_replace;
use function strlen;

/**
 * Валидатор телефона.
 */
class PhoneValidator extends AbstractValidator
{
    /** @inheritDoc */
    public bool $formatOnValidate = true;

    /**
     * @inheritDoc
     */
    public function parseValue(mixed $value): ?int
    {
        $val = (int)preg_replace('~\D+~u', '', (string)$value);
        if (empty($val)) {
            return null;
        }

        $len = strlen((string)$val);
        if ($len < 7 || $len > 12) {
            throw new ValidateException('Некорректный телефон');
        }

        return $val;
    }

    /**
     * @inheritDoc
     */
    public function formatValue(mixed $value): string
    {
        $value = (string)$this->parseValue($value);
        if ($value !== '' && strlen($value) > 7) {
            $value = '+' . $value;
        }

        return $value;
    }
}
