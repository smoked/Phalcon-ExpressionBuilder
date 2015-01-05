<?php

namespace Phalcon\Component\ExpressionBuilder\Conditions;

use Phalcon\Component\ExpressionBuilder\Exception\ErrorException;

/**
 * Creates a BETWEEN comparison expression with the given arguments.
 *
 * Class Between
 * @package Phalcon\ExpressionBuilder\Conditions
 */
class Between extends Condition {

    /**
     * The operator attached to the class, and can not be modified
     */
    const OPERATOR = 'BETWEEN';

    /**
     * @see parent::getConditions()
     *
     * @return string
     */
    public function getConditions() {
        return $this->getField() . $this->getOperator() . implode(" AND ", $this->getBindParam());
    }

    public function setValue($value) {
        if(!is_array($value) || empty($value) || count($value) != 2) {
            throw new ErrorException('Parameter $value is invalid');
        }
        $filter = array_filter($value, function($a){
            if(is_array($a)) return $a;
        });
        if(!empty($filter)) {
            throw new ErrorException('Parameter $value is invalid');
        }
        return parent::setValue($value);
    }
}