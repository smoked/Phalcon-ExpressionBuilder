<?php

namespace Phalcon\Component\ExpressionBuilder\Conditions;

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
     * @see parent::getBindParamCondition()
     *
     * @return array|string|void
     */
    public function getBindParamCondition() {
        return "(". implode(",", $this->getBindParam()) .")";
    }

    /**
     * @see parent::getParamCondition()
     *
     * @return array|string|void
     */
    public function getParamCondition() {
        return "(". implode(",", $this->getParam()) .")";
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
        if(is_array($value) && !empty($value)) {
            return true;
        }

        return parent::validateValue($value);
    }
}
