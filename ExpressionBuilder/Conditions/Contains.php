<?php

namespace Phalcon\ExpressionBuilder\Conditions;
use Phalcon\ExpressionBuilder\Exception\ErrorException;

/**
 * Creates a IN() comparison expression with the given arguments.
 *
 * Class Contains
 * @package Phalcon\ExpressionBuilder\Conditions
 */
class Contains extends Condition {

    /**
     * The operator attached to the class, and can not be modified
     */
    const OPERATOR = 'IN';

    public function __construct($field, $value) {
        if(!is_array($value)) {
            $value = [$value];
        }
        $value = array_filter($value, function ($a) { return $a !== null ? true: false;});
        parent::__construct($field, $value);
    }

    /**
     * @see parent::getConditions()
     *
     * @return string
     */
    public function getConditions() {
        return $this->getField() . $this->getOperator() . "(". implode(",", $this->getBindParam()) .")";
    }
}
