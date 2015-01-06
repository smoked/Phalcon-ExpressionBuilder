<?php

namespace Phalcon\Component\ExpressionBuilder\Conditions;

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
     * Checked value validate
     *
     * @param $value
     * @return bool
     */
    public function validateValue($value) {
        if( $value === null ) {
            return true;
        }
        return parent::validateValue($value);
    }

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