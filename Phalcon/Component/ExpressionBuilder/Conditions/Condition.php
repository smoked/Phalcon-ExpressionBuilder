<?php

namespace Phalcon\Component\ExpressionBuilder\Conditions;

use Phalcon\Component\ExpressionBuilder\Exception\ErrorException;
use Phalcon\Component\ExpressionBuilder\Expression;

/**
 * Class Condition
 * @package Phalcon\ExpressionBuilder\Conditions
 */
class Condition extends Expression {

    /**
     * The operator attached to the class, and can not be modified
     */
    const OPERATOR = '';

    /**
     * Initializes a new Condition
     *
     *
     * @param \Phalcon\ExpressionBuilder\Field $field
     * @param null $value
     */
    public function __construct($field, $value = null) {
        parent::__construct($field, $value);
        parent::setOperator(static::OPERATOR);
    }

    /**
     * The operator attached to the class, and can not be modified
     */
    public function setOperator() {}

    /**
     * Checked value validate
     *
     * @param $value
     * @return bool
     */
    public function validateValue($value) {
        if( empty($value) ) {
            return false;
        }

        if( !is_scalar($value) ) {
            return false;
        }

        return true;
    }

    /**
     * @see parent::setValue()
     *
     * @param mixed $value
     * @return $this
     * @throws ErrorException
     */
    public function setValue($value) {
        if($this->validateValue($value)) {
            return parent::setValue($value);
        } else {
            throw new ErrorException('Parameter $value is invalid');
        }
    }
}