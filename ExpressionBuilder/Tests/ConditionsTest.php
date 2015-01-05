<?php

namespace Phalcon\ExpressionBuilder\Tests;


use Phalcon\ExpressionBuilder\Conditions;

class ConditionsTest extends \PHPUnit_Framework_TestCase
{
    public function testCondition() {
        $cond = new Conditions\Condition('a', 'B');
        $cond->setOperator('OP');

        $this->assertNotEquals($cond->getOperator(), 'OP');
        $this->assertEquals($cond->getOperator(true), Conditions\Condition::OPERATOR);
    }
}