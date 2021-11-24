<?php

namespace App\Utils;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ConstraintViolationUtils
{
    /**
     * Create violation list.
     *
     * By default, API Platform validation returns 422 with violations. If we perform custom validation, we build the
     * ConstraintViolationList list manually.
     *
     * @param string $message
     * @param string $propertyPath
     * @param mixed $invalidValue
     * @param null|ConstraintViolationList $violationList
     *
     * @return ConstraintViolationList
     */
    public static function createViolationList(string $message, string $propertyPath, mixed $invalidValue, ?ConstraintViolationList $violationList = null): ConstraintViolationList
    {
        $violationList = $violationList ?? new ConstraintViolationList();

        $violationParameters = [
            '{{ value}}' => $invalidValue
        ];

        $violation = new ConstraintViolation(
            $message,
            $message,
            $violationParameters,
            $invalidValue,
            $propertyPath,
            $invalidValue
        );

        $violationList->add($violation);

        return $violationList;
    }
}
