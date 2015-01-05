<?php

namespace Phalcon\ExpressionBuilder\Conditions;

/**
 * Creates a LIKE() comparison expression with the given arguments.
 *
 * Class Like
 * @package Phalcon\ExpressionBuilder\Conditions
 */
class Like extends Condition {

    /**
     * The operator attached to the class, and can not be modified
     */
    const OPERATOR = 'LIKE';
}