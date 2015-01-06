<?php

namespace Phalcon\Component\ExpressionBuilder\Conditions;

/**
 * Creates a non equality comparison expression with the given arguments.
 *
 * Class NotEqual
 * @package Phalcon\ExpressionBuilder\Conditions
 */
class NotEqual extends Equal {

    /**
     * The operator attached to the class, and can not be modified
     */
    const OPERATOR = '!=';

    /**
     * @see Expression::getOperator()
     *
     * @param bool $trim
     * @return string
     */
    public function getOperator($trim = false) {
        $operator = $this->getValue() === null ? ' IS NOT NULL ' : parent::getOperator($trim);
        return $trim ? trim($operator) : $operator;
    }
}