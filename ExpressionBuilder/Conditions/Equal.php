<?php

namespace Phalcon\ExpressionBuilder\Conditions;

use Phalcon\ExpressionBuilder\Exception\ErrorException;

/**
 *  Creates an equality comparison expression with the given arguments.
 *
 * Class Equal
 * @package Phalcon\ExpressionBuilder\Conditions
 */
class Equal extends Condition {

    /**
     * The operator attached to the class, and can not be modified
     */
    const OPERATOR = '=';

    /**
     * @see Expression::getOperator()
     *
     * @param bool $trim
     * @return string
     */
    public function getOperator($trim = false) {
        $operator = $this->getValue() === null ? ' IS NULL '  : parent::getOperator($trim);
        return $trim ? trim($operator) : $operator;
    }
}