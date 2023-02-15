<?php

namespace App\Infrastructure\Validator\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationException extends Exception
{
    public function __construct(private ConstraintViolationListInterface $violationList, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
       parent::__construct($message, $code, $previous);
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violationList;
    }
}