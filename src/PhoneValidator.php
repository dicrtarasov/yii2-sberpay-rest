<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 15.10.20 15:07:03
 */

declare(strict_types = 1);
namespace dicr\sberbank;

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
    public $formatOnValidate = true;

    /**
     * @inheritDoc
     * @return int|null
     */
    public function parseValue($value) : ?int
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
    public function formatValue($value) : string
    {
        $value = (string)$this->parseValue($value);
        if ($value !== '' && strlen($value) > 7) {
            $value = '+' . $value;
        }

        return $value;
    }
}
