<?php

namespace Phalcon\Component\ExpressionBuilder\Tests;


use Phalcon\Component\ExpressionBuilder\Conditions as C;

class ConditionsTest extends \PHPUnit_Framework_TestCase
{
    public function testCondition() {
        $cond = new C\Condition('a', 'B');
        $cond->setOperator('OP');

        $this->assertNotEquals($cond->getOperator(), 'OP');
        $this->assertEquals($cond->getOperator(true), C\Condition::OPERATOR);
    }

    public function testEscapeString() {
        $cond = C\Equal::init('a', "'escapeme");

        $cond->setEscapeCallback(function($val) { return $val . 'esc'; });
        $this->assertEquals($cond->getConditions(false), "a = ''escapemeesc'");

        $cond->setEscapeCallback(function($val) { return addslashes($val); });
        $this->assertEquals($cond->getConditions(false), "a = '\'escapeme'");
    }

    /**
     * @dataProvider getConditionQuoted
     */
    public function testConditionQuoted($cond, $quote, $assert) {
        $cond->setQuote($quote);
        $this->assertEquals($cond->getConditions(false), $assert);
    }

    public function getConditionQuoted() {
        return array(
            array(C\Condition::init('a', 'B'), null, "a  B"),
            array(C\Condition::init('a', 'B'), "'", "a  'B'"),
            array(C\Condition::init('a', 'B'), "`", "a  `B`"),
            array(C\Condition::init('a', '10'), "'", "a  '10'"),
            array(C\Condition::init('a', 10), "'", "a  10"),
            array(C\Condition::init('a', 10.5), "'", "a  10.5"),
            array(C\Equal::init('a', '1'), "'", "a = '1'"),
            array(C\Equal::init('a', null), "'", "a IS NULL "),
            array(C\NotEqual::init('a', '1'), "'", "a != '1'"),
            array(C\NotEqual::init('a', 1), "'", "a != 1"),
            array(C\NotEqual::init('a', null), "'", "a IS NOT NULL "),
        );
    }
}