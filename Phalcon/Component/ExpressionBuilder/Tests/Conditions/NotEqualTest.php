<?php

namespace Phalcon\Component\ExpressionBuilder\Tests;

use Phalcon\Component\ExpressionBuilder\Conditions;

class NotEqualTest extends AbstractConditionsTest
{
    public function getTestData() {
        return array(
            array('a', 1, Conditions\NotEqual::OPERATOR, new Conditions\NotEqual('a', 'B')),
            array('a1', 1, Conditions\NotEqual::OPERATOR, Conditions\NotEqual::init('a1', 'B1')),
            array('a2', 0, 'IS NOT NULL', new Conditions\NotEqual('a2', null))
        );
    }

    public function getWrongData() {
        return array(
            array(Conditions\NotEqual::init('n', 1), [1])
        );
    }
}