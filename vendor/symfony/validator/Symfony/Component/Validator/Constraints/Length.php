<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 *
 * @api
 */
class Length extends Constraint
{
    public $maxMessage = 'Hasło jest za długie. Powinno mieć {{ limit }} lub mniej znaków.|Hasło jest za długie. Powinno mieć {{ limit }} lub mniej znaków.';
    public $minMessage = 'Hasło jest za krótkie. Powinno mieć {{ limit }} lub więcej znaków.|Hasło jest za krótkie. Powinno mieć {{ limit }} lub więcej znaków.';
    public $exactMessage = 'This value should have exactly {{ limit }} character.|This value should have exactly {{ limit }} characters.';
    public $max;
    public $min;
    public $charset = 'UTF-8';

    public function __construct($options = null)
    {
        if (null !== $options && !is_array($options)) {
            $options = array(
                'min' => $options,
                'max' => $options,
            );
        }

        parent::__construct($options);

        if (null === $this->min && null === $this->max) {
            throw new MissingOptionsException('Either option "min" or "max" must be given for constraint ' . __CLASS__, array('min', 'max'));
        }
    }
}
