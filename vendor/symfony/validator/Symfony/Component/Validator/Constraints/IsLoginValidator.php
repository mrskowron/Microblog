<?php

namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsLoginValidator extends ConstraintValidator
{
    
    public function validate($value, Constraint $constraint)
    {
        
        if (false === $value || (empty($value) && '0' != $value)) {
            $this->context->addViolation($constraint->message);
        }
    }
}
