<?php

namespace Phalcon\ExpressionBuilder\Conditions;

use Phalcon\ExpressionBuilder\Exception\ErrorException;

/**
 * Creates a greater-than comparison expression with the given arguments.
 *
 * Class Greater
 * @package Phalcon\ExpressionBuilder\Conditions
 */
class Greater extends Condition {

    /**
     * The operator attached to the class, and can not be modified
     */
    const OPERATOR = '>';
}