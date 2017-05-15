<?php


namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


class IsLogin extends Constraint
{
    public $message = 'Użytkownik o takim loginie już istnieje';
}
