<?php

namespace Phalcon\Component\ExpressionBuilder\Conditions;

/**
 * Creates a greater-than-equal comparison expression with the given arguments.
 *
 * Class GreaterOrEqual
 * @package Phalcon\ExpressionBuilder\Conditions
 */
class GreaterOrEqual extends Condition {

    /**
     * The operator attached to the class, and can not be modified
     */
    const OPERATOR = '>=';
}