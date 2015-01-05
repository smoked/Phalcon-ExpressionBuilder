<?php

namespace Phalcon\Component\ExpressionBuilder\Tests;

use Phalcon\Component\ExpressionBuilder\Conditions;

class EqualTest extends AbstractConditionsTest
{
    public function getTestData() {
        return array(
            array('a', 1, Conditions\Equal::OPERATOR, new Conditions\Equal('a', 'B')),
            array('a1', 1, Conditions\Equal::OPERATOR, Conditions\Equal::init('a1', 'B1')),
            array('a2', 0, 'IS NULL', new Conditions\Equal('a2', null))
        );
    }

    public function getWrongData() {
        return array(
            array(Conditions\Equal::init('n', 1), [1])
        );
    }
}