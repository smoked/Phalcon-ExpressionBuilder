<?php

namespace Phalcon\ExpressionBuilder\Tests;

use Phalcon\ExpressionBuilder\Conditions;

class LessOrEqualTest extends AbstractConditionsTest
{
    public function getTestData() {
        return array(
            array('a', 1, Conditions\LessOrEqual::OPERATOR, new Conditions\LessOrEqual('a', 2)),
            array('a1', 1, Conditions\LessOrEqual::OPERATOR, Conditions\LessOrEqual::init('a1', 3))
        );
    }

    public function getWrongData() {
        return array(
            array(Conditions\LessOrEqual::init('n', 1), null),
            array(Conditions\LessOrEqual::init('n', 1), [1,2,3])
        );
    }
}