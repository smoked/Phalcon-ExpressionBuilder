<?php

namespace Phalcon\Component\ExpressionBuilder\Tests;

use Phalcon\Component\ExpressionBuilder\Conditions;

class LikeTest extends AbstractConditionsTest
{
    public function getTestData() {
        return array(
            array('a', 1, Conditions\Like::OPERATOR, new Conditions\Like('a', 2)),
            array('a1', 1, Conditions\Like::OPERATOR, Conditions\Like::init('a1', 3))
        );
    }

    public function getWrongData() {
        return array(
            array(Conditions\Like::init('n', '1'), null),
            array(Conditions\Like::init('n', '1'), [1,2])
        );
    }
}