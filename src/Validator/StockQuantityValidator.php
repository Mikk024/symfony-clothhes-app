<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class StockQuantityValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value->getProduct()->getStockQuantity() < $value->getQuantity()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
