<?php

namespace Phalcon\ExpressionBuilder\Tests;

use Phalcon\ExpressionBuilder\Conditions;

class GreaterTest extends AbstractConditionsTest
{

    public function getTestData() {
        return array(
            array('a', 1, Conditions\Greater::OPERATOR, new Conditions\Greater('a', 2)),
            array('a1', 1, Conditions\Greater::OPERATOR, Conditions\Greater::init('a1', 3))
        );
    }

    public function getWrongData() {
        return array(
            array(Conditions\Greater::init('n', 1), null),
            array(Conditions\Greater::init('n', 1), [1,2,3])
        );
    }
}