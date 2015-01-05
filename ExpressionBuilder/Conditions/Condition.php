<?php

namespace Phalcon\ExpressionBuilder\Conditions;

use Phalcon\ExpressionBuilder\Exception\ErrorException;
use Phalcon\ExpressionBuilder\Expression;

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
     * @see parent::setValue()
     *
     * @param mixed $value
     * @return $this
     * @throws ErrorException
     */
    public function setValue($value) {
        if( empty($value) && static::OPERATOR != '=' && static::OPERATOR != '!=' ) {
            throw new ErrorException('Parameter $value is invalid');
        }
        if( !is_scalar($value) && $value !== null && static::OPERATOR != 'IN' && static::OPERATOR != 'BETWEEN') {
            throw new ErrorException('Parameter $value is invalid');
        }

        return parent::setValue($value);
    }
}