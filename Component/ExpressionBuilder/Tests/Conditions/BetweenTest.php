<?php

namespace Phalcon\Component\ExpressionBuilder\Tests;

use Phalcon\Component\ExpressionBuilder\Conditions;

class BetweenTest extends AbstractConditionsTest
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
        $this->assertEquals($cond->getConditions(), "{$field} {$operator} " . implode(" AND ", $cond->getBindParam()));
        $this->assertEquals(count($cond->getBind()), $bind_count);
    }

    public function getTestData() {
        return array(
            array('a', 2, Conditions\Between::OPERATOR, new Conditions\Between('a', [1, 2])),
            array('a1', 2, Conditions\Between::OPERATOR, Conditions\Between::init('a1', [3, 4]))
        );
    }

    public function getWrongData() {
        return array(
            array(Conditions\Between::init('a1', [1, 2]), null),
            array(Conditions\Between::init('a1', [1, 2]), "1"),
            array(Conditions\Between::init('a1', [1, 2]), [1,2,3]),
            array(Conditions\Between::init('a1', [1, 2]), [1]),
            array(Conditions\Between::init('a1', [1, 2]), [[1],2]),
        );
    }
}