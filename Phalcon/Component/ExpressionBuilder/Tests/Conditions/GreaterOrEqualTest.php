<?php

namespace Phalcon\Component\ExpressionBuilder\Tests;

use Phalcon\Component\ExpressionBuilder\Conditions;

class GreaterOrEqualTest extends AbstractConditionsTest
{
    public function getTestData() {
        return array(
            array('a', 1, Conditions\GreaterOrEqual::OPERATOR, new Conditions\GreaterOrEqual('a', 2)),
            array('a1', 1, Conditions\GreaterOrEqual::OPERATOR, Conditions\GreaterOrEqual::init('a1', 3))
        );
    }

    public function getWrongData() {
        return array(
            array(Conditions\GreaterOrEqual::init('n', 1), null),
            array(Conditions\GreaterOrEqual::init('n', 1), [1,2,3])
        );
    }
}