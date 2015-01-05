<?php

namespace Phalcon\ExpressionBuilder\Tests;

use Phalcon\ExpressionBuilder\Conditions;

abstract class AbstractConditionsTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @dataProvider getWrongData
     * @expectedException \Phalcon\ExpressionBuilder\Exception\ErrorException
     * @expectedExceptionMessage Parameter $value is invalid
     */
    public function testWrongValue($cond, $param) {
        $cond->setValue($param);
    }

    /**
     *
     * @dataProvider getTestData
     */
    public function testConditions($field, $bind_count, $operator, $cond) {
        $this->assertEquals($cond->getOperator(true), $operator);
        $this->assertEquals($cond->getOperator(), " {$operator} ");

        $this->assertEquals($cond->getConditions(), "{$field} {$operator} " . $cond->getBindParam());
        $this->assertEquals(count($cond->getBind()), $bind_count);
    }

    abstract public function getTestData();
    abstract public function getWrongData();
}