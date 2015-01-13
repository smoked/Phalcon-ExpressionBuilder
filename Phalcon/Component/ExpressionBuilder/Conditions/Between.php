<?php

namespace Phalcon\Component\ExpressionBuilder\Conditions;

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
     * @see parent::getBindParamCondition()
     *
     * @return array|string|void
     */
    public function getBindParamCondition() {
        return implode(" AND ", $this->getBindParam());
    }

    /**
     * @see parent::getParamCondition()
     *
     * @return array|string|void
     */
    public function getParamCondition() {
        return implode(" AND ", $this->getParam());
    }

    /**
     * Checked value validate
     *
     * @param $value
     * @return bool
     */
    public function validateValue($value) {
        if(!is_array($value)) {
            return false;
        }
        $filter = array_filter($value, function ($a) {
            if (is_array($a)) return $a;
        });
        if (!empty($filter)) {
            return false;
        }

        if(count($value) == 2) {
            return true;
        }

        return parent::validateValue($value);
    }
}