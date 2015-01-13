<?php

namespace Phalcon\Component\ExpressionBuilder\Tests;

use Phalcon\Component\ExpressionBuilder\Conditions;

class ContainsTest extends AbstractConditionsTest
{

    /**
     *
     * @dataProvider getTestData
     */
    public function testConditions($field, $bind_count, $operator, $cond) {
        $this->assertEquals($cond->getOperator(true), $operator);
        $this->assertEquals($cond->getOperator(), " {$operator} ");

        $this->assertEquals(is_array($cond->getValue()), true);
        $this->assertEquals(count($cond->getBindParam()), $bind_count);
        $this->assertEquals($cond->getConditions(), "{$field} {$operator} (" . implode(",", $cond->getBindParam()) . ")");
        $this->assertEquals(count($cond->getBind()), $bind_count);
    }

    public function getTestData() {
        return array(
            array('a', 3, Conditions\Contains::OPERATOR, new Conditions\Contains('a', [1, 2, 3])),
            array('a1', 2, Conditions\Contains::OPERATOR, Conditions\Contains::init('a1', [1, 2])),
            array('a2', 3, Conditions\Contains::OPERATOR, Conditions\Contains::init('a2', ['a', 'b', 'c']))
        );
    }

    public function getWrongData() {
        return array(
            array(Conditions\Contains::init('n', [1, 2]), null),
            array(Conditions\Contains::init('n', [1, 2]), []),
            array(Conditions\Contains::init('n', [1, 2]), [[1],2,3]),
        );
    }
}