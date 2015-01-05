<?php

namespace Phalcon\ExpressionBuilder\Tests;

use Phalcon\ExpressionBuilder\Conditions;

class LessTest extends AbstractConditionsTest
{
    public function getTestData() {
        return array(
            array('a', 1, Conditions\Less::OPERATOR, new Conditions\Less('a', 2)),
            array('a1', 1, Conditions\Less::OPERATOR, Conditions\Less::init('a1', 3))
        );
    }

    public function getWrongData() {
        return array(
            array(Conditions\Less::init('n', 1), null),
            array(Conditions\Less::init('n', 1), [1,2])
        );
    }
}